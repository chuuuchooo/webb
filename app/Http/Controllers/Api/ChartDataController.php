<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FamilyPlanning;
use App\Models\ChildProfile;
use App\Models\Vaccination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartDataController extends Controller
{
    /**
     * Get Family Planning statistics for user dashboard
     */
    public function getUserFamilyPlanningStats()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        // 1. Records encoded this month
        $recordsThisMonth = FamilyPlanning::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        // 2. WRA vs NWRA for females only
        $wraFemale = FamilyPlanning::where('sex', 'Female')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 49')
            ->count();
        $nwraFemale = FamilyPlanning::where('sex', 'Female')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) NOT BETWEEN 15 AND 49')
            ->count();

        // 3. Modern/non-modern FP by age group (10-14, 15-19, 20+)
        $modernExclusions = ['CMM/BILLINGS', 'BBT', 'Sympto-Thermal', 'SDM', 'LAM'];
        $ageGroups = [
            ['label' => '10-14', 'min' => 10, 'max' => 14],
            ['label' => '15-19', 'min' => 15, 'max' => 19],
            ['label' => '20+', 'min' => 20, 'max' => 200],
        ];
        $modernFpByAgeGroup = [];
        $nonModernFpByAgeGroup = [];
        foreach ($ageGroups as $group) {
            $modernFpByAgeGroup[$group['label']] = FamilyPlanning::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN ? AND ?', [$group['min'], $group['max']])
                ->where(function($query) use ($modernExclusions) {
                    foreach ($modernExclusions as $ex) {
                        $query->where('fp_method', 'NOT LIKE', "%$ex%", 'and');
                    }
                })
                ->count();
            $nonModernFpByAgeGroup[$group['label']] = FamilyPlanning::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN ? AND ?', [$group['min'], $group['max']])
                ->where(function($query) use ($modernExclusions) {
                    $query->where(function($sub) use ($modernExclusions) {
                        foreach ($modernExclusions as $ex) {
                            $sub->orWhere('fp_method', 'LIKE', "%$ex%");
                        }
                    });
                })
                ->count();
        }

        // 4. Records per purok
        $recordsPerPurok = FamilyPlanning::select('purok', DB::raw('COUNT(*) as count'))
            ->groupBy('purok')
            ->get();

        // 5. Pie: fp_method
        $fpMethodPie = FamilyPlanning::select('fp_method', DB::raw('COUNT(*) as count'))
            ->groupBy('fp_method')
            ->get();

        // 6. Pie: intended_method
        $intendedMethodPie = FamilyPlanning::select('intended_method', DB::raw('COUNT(*) as count'))
            ->groupBy('intended_method')
            ->get();

        // 7. Descriptions (interactive, responsive)
        $descriptions = [
            'recordsThisMonth' => "There are $recordsThisMonth records encoded for this month.",
            'wraVsNwraFemale' => "Of all female records, $wraFemale are Women of Reproductive Age (15-49) and $nwraFemale are Not WRA.",
            'modernFpByAgeGroup' => "Modern FP method usage by age group: " . json_encode($modernFpByAgeGroup),
            'nonModernFpByAgeGroup' => "Non-modern FP method usage by age group: " . json_encode($nonModernFpByAgeGroup),
            'recordsPerPurok' => "Records per purok: " . json_encode($recordsPerPurok),
            'fpMethodPie' => "Distribution of FP methods used.",
            'intendedMethodPie' => "Distribution of intended FP methods.",
        ];

        // 8. Completed Records (no nulls in required/optional fields)
        $completedRecordsCount = FamilyPlanning::all()->filter(function($record) {
            return method_exists($record, 'getCompletionStatus') && $record->getCompletionStatus() === 'Complete';
        })->count();
        $descriptions['completedRecords'] = "There are $completedRecordsCount completed family planning records (no missing fields).";

        return response()->json([
            'recordsThisMonth' => $recordsThisMonth,
            'wraVsNwraFemale' => [
                'WRA' => $wraFemale,
                'NWRA' => $nwraFemale
            ],
            'modernFpByAgeGroup' => $modernFpByAgeGroup,
            'nonModernFpByAgeGroup' => $nonModernFpByAgeGroup,
            'recordsPerPurok' => $recordsPerPurok,
            'fpMethodPie' => $fpMethodPie,
            'intendedMethodPie' => $intendedMethodPie,
            'completedRecords' => $completedRecordsCount,
            'descriptions' => $descriptions,
        ]);
    }

    /**
     * Get Family Planning statistics
     */
    public function getFamilyPlanningStats()
    {
        // Monthly records
        $monthlyRecords = FamilyPlanning::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // If no records, create empty data structure
        if ($monthlyRecords->isEmpty()) {
            $monthlyRecords = collect([
                (object)[
                    'month' => Carbon::now()->month,
                    'year' => Carbon::now()->year,
                    'count' => 0
                ]
            ]);
        }

        // FP Methods used
        $fpMethods = FamilyPlanning::select('fp_method', DB::raw('COUNT(*) as count'))
            ->groupBy('fp_method')
            ->get();

        // If no methods, create empty data structure
        if ($fpMethods->isEmpty()) {
            $fpMethods = collect([
                (object)[
                    'fp_method' => 'No Data',
                    'count' => 0
                ]
            ]);
        }

        // WRA vs NWRA (15-49)
        $wraCount = FamilyPlanning::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 49')
            ->count();
        $nwraCount = FamilyPlanning::whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) NOT BETWEEN 15 AND 49')
            ->count();

        // Status of records
        $statusCounts = [
            'Complete' => 0,
            'Partially Complete' => 0,
            'Incomplete' => 0
        ];

        // Get all records and count their status
        $records = FamilyPlanning::all();
        foreach ($records as $record) {
            $status = $record->getCompletionStatus();
            $statusCounts[$status]++;
        }

        return response()->json([
            'monthlyRecords' => $monthlyRecords,
            'fpMethods' => $fpMethods,
            'wraStats' => [
                'WRA' => $wraCount,
                'NWRA' => $nwraCount
            ],
            'statusCounts' => $statusCounts
        ]);
    }

    /**
     * Get Immunization statistics
     */
    public function getImmunizationStats()
    {
        // Monthly child records
        $monthlyRecords = ChildProfile::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // If no records, create empty data structure
        if ($monthlyRecords->isEmpty()) {
            $monthlyRecords = collect([
                (object)[
                    'month' => Carbon::now()->month,
                    'year' => Carbon::now()->year,
                    'count' => 0
                ]
            ]);
        }

        // Vaccines given
        $vaccinesGiven = Vaccination::select('vaccine_type', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('vaccine_type', 'status')
            ->get();

        // If no vaccines, create empty data structure
        if ($vaccinesGiven->isEmpty()) {
            $vaccinesGiven = collect([
                (object)[
                    'vaccine_type' => 'No Data',
                    'status' => 'Not Completed',
                    'count' => 0
                ]
            ]);
        }

        // Records per purok
        $purokRecords = ChildProfile::select('purok', DB::raw('COUNT(*) as count'))
            ->groupBy('purok')
            ->get();

        // If no purok records, create empty data structure
        if ($purokRecords->isEmpty()) {
            $purokRecords = collect([
                (object)[
                    'purok' => 'No Data',
                    'count' => 0
                ]
            ]);
        }

        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // 1. Number of child profiles inputted this month
        $recordsThisMonth = ChildProfile::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        // 2. Number of children with completed vaccination status
        // Define completed as all required vaccines are present and status is 'Completed'
        $children = ChildProfile::with('vaccinations')->get();
        $completedCount = 0;
        $partialCount = 0;
        $notVaccinatedCount = 0;
        $statusPerChild = [];
        foreach ($children as $child) {
            $vaccs = $child->vaccinations;
            if ($vaccs->isEmpty()) {
                $notVaccinatedCount++;
                $statusPerChild[$child->id] = 'Not Vaccinated';
            } else {
                $completed = $vaccs->where('status', 'Completed')->count();
                $total = $vaccs->count();
                // If all vaccinations are 'Completed' and there is at least one vaccination
                if ($total > 0 && $completed === $total) {
                    $completedCount++;
                    $statusPerChild[$child->id] = 'Completed';
                } elseif ($completed > 0) {
                    $partialCount++;
                    $statusPerChild[$child->id] = 'Partially Completed';
                } else {
                    $notVaccinatedCount++;
                    $statusPerChild[$child->id] = 'Not Vaccinated';
                }
            }
        }

        // 3. Pie chart: children per purok
        $childrenPerPurok = ChildProfile::select('purok', DB::raw('COUNT(*) as count'))
            ->groupBy('purok')
            ->get();

        // 4. Pie chart: vaccination status (not yet, partial, complete)
        $vaccStatusPie = [
            'Not Vaccinated' => $notVaccinatedCount,
            'Partially Completed' => $partialCount,
            'Completed' => $completedCount
        ];

        // 5. Bar graph: vaccines administered to children (by type and count)
        $vaccineCounts = Vaccination::select('vaccine_type', DB::raw('COUNT(*) as count'))
            ->groupBy('vaccine_type')
            ->get();

        // 6. Descriptions
        $descriptions = [
            'recordsThisMonth' => "There are $recordsThisMonth child profiles inputted this month.",
            'completedChildren' => "There are $completedCount children who have completed all required vaccinations.",
            'childrenPerPurok' => "Distribution of children records per purok.",
            'vaccStatusPie' => "Breakdown of children by vaccination status (not yet vaccinated, partially completed, completed).",
            'vaccineCounts' => "Count of each vaccine type administered to children."
        ];

        return response()->json([
            'recordsThisMonth' => $recordsThisMonth,
            'completedChildren' => $completedCount,
            'childrenPerPurok' => $childrenPerPurok,
            'vaccStatusPie' => $vaccStatusPie,
            'vaccineCounts' => $vaccineCounts,
            'descriptions' => $descriptions
        ]);
    }
} 