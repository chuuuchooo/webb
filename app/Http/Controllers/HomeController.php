<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FamilyPlanning;
use App\Models\ChildProfile;
use App\Models\ImmunizationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Export Family Planning records to CSV for user.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function exportFamilyPlanningCsv()
    {
        $records = \App\Models\FamilyPlanning::all();
        $filename = 'family_planning_records.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $exclude = ['created_at', 'updated_at', 'intended_fp_method'];
        $columns = $records->first() ? array_diff(array_keys($records->first()->getAttributes()), $exclude) : [];
        $dateColumns = ['birthdate', 'date_served', 'date_counselled_pregnant', 'date_encoded'];

        $callback = function() use ($records, $columns, $dateColumns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($records as $record) {
                $row = [];
                foreach ($columns as $col) {
                    $value = $record->$col;
                    if (in_array($col, $dateColumns) && !empty($value)) {
                        try {
                            $date = $value instanceof \Carbon\Carbon ? $value : \Carbon\Carbon::parse($value);
                            $value = $date->format('Y-m-d');
                        } catch (\Exception $e) {}
                        $value = "\t" . $value;
                    } elseif (is_numeric($value) && !empty($value)) {
                        $value = "\t" . $value;
                    }
                    $row[] = (string) $value;
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Immunization records to CSV for user.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function exportImmunizationCsv()
    {
        $records = \App\Models\ChildProfile::all();
        $filename = 'immunization_records.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $exclude = ['created_at', 'updated_at'];
        $columns = $records->first() ? array_diff(array_keys($records->first()->getAttributes()), $exclude) : [];
        $dateColumns = ['birthdate'];
        $numericColumns = ['birth_weight', 'birth_height'];

        $callback = function() use ($records, $columns, $dateColumns, $numericColumns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($records as $record) {
                $row = [];
                foreach ($columns as $col) {
                    $value = $record->$col;
                    if (in_array($col, $dateColumns) && !empty($value)) {
                        try {
                            $date = $value instanceof \Carbon\Carbon ? $value : \Carbon\Carbon::parse($value);
                            $value = $date->format('Y-m-d');
                        } catch (\Exception $e) {}
                        $value = "\t" . $value;
                    } elseif ((in_array($col, $numericColumns) || is_numeric($value)) && !empty($value)) {
                        $value = "\t" . $value;
                    }
                    $row[] = (string) $value;
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $monthlyRecords = $this->getMonthlyRecords();
        $yearlyRecords = $this->getYearlyRecords();
        
        // Get total records count (sum of FamilyPlanning and ChildProfile)
        $familyPlanningCount = FamilyPlanning::count();
        $immunizationCount = \App\Models\ChildProfile::count();
        $totalRecords = $familyPlanningCount + $immunizationCount;
        \Log::info('DASHBOARD COUNTS - FamilyPlanning: ' . $familyPlanningCount . ', ChildProfile: ' . $immunizationCount . ', Total: ' . $totalRecords);
        $totalUsers = User::where('isAdmin', false)->count();

        // Get data for charts
        $fpChartData = $this->getFamilyPlanningChartData();
        $immunizationChartData = $this->getImmunizationChartData();

        if ($user->isAdmin) {
            $employeeStats = $this->getEmployeeStatistics();
            return view('home', compact(
                'monthlyRecords',
                'yearlyRecords',
                'employeeStats',
                'totalRecords',
                'familyPlanningCount',
                'immunizationCount',
                'totalUsers',
                'fpChartData',
                'immunizationChartData'
            ));
        }

        return view('home', compact(
            'monthlyRecords',
            'yearlyRecords',
            'totalRecords',
            'familyPlanningCount',
            'immunizationCount',
            'fpChartData',
            'immunizationChartData'
        ));
    }

    /**
     * Get data for user dashboard
     */
    private function getUserDashboardData()
    {
        // Get monthly and yearly records
        $monthlyRecords = $this->getMonthlyRecords();
        $yearlyRecords = $this->getYearlyRecords();
        
        return [
            'monthlyRecords' => $monthlyRecords,
            'yearlyRecords' => $yearlyRecords
        ];
    }
    
    /**
     * Get monthly records for both family planning and immunization
     */
    public function getMonthlyRecords()
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();

        $monthlyFp = FamilyPlanning::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $monthlyImm = ChildProfile::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
            ->get();
        
        return [
            'familyPlanning' => $monthlyFp,
            'immunization' => $monthlyImm
        ];
    }
    
    /**
     * Get yearly records for both family planning and immunization
     */
    public function getYearlyRecords()
    {
        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now();

        $yearlyFp = FamilyPlanning::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('year')
        ->orderBy('year')
        ->get();

        $yearlyImm = ChildProfile::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('year')
        ->orderBy('year')
        ->get();
        
        return [
            'familyPlanning' => $yearlyFp,
            'immunization' => $yearlyImm
        ];
    }
    
    /**
     * Get employee statistics for admin dashboard
     */
    public function getEmployeeStatistics()
    {
        // Get total employees (excluding admin users)
        $totalEmployees = User::where('isAdmin', false)->count();

        // Get active employees (users who have logged in within the last 30 days)
        $activeEmployees = User::where('isAdmin', false)
            ->where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Calculate health worker activity percentage
        $healthWorkerActivity = $totalEmployees > 0 
            ? round(($activeEmployees / $totalEmployees) * 100, 1)
            : 0;

        return [
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'healthWorkerActivity' => $healthWorkerActivity
        ];
    }

    /**
     * Show the BMI calculator page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bmiCalculator()
    {
        return view('bmi-calculator');
    }

    /**
     * Get Family Planning chart data
     */
    private function getFamilyPlanningChartData()
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

        return [
            'monthlyRecords' => $monthlyRecords,
            'fpMethods' => $fpMethods,
            'wraStats' => [
                'WRA' => $wraCount,
                'NWRA' => $nwraCount
            ],
            'statusCounts' => $statusCounts
        ];
    }

    /**
     * Get Immunization chart data
     */
    private function getImmunizationChartData()
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
        $vaccinesGiven = DB::table('vaccinations')
            ->select('vaccine_type', DB::raw('COUNT(*) as count'))
            ->where('status', 'Completed')
            ->groupBy('vaccine_type')
            ->get();

        // If no vaccines, create empty data structure
        if ($vaccinesGiven->isEmpty()) {
            $vaccinesGiven = collect([
                (object)[
                    'vaccine_type' => 'No Data',
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

        // Vaccination status
        $statusCounts = [
            'Fully Vaccinated' => 0,
            'Partially Vaccinated' => 0,
            'Not Vaccinated' => 0
        ];

        // Get all child profiles and count their vaccination status
        $children = ChildProfile::all();
        foreach ($children as $child) {
            $status = $child->vaccination_status['status'];
            $statusCounts[$status]++;
        }

        return [
            'monthlyRecords' => $monthlyRecords,
            'vaccinesGiven' => $vaccinesGiven,
            'purokRecords' => $purokRecords,
            'statusCounts' => $statusCounts
        ];
    }
}
