<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FamilyPlanning;
use App\Models\ImmunizationRecord;
use App\Models\FamilyPlanningEdit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FamilyPlanningExport;
use App\Exports\ImmunizationExport;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Display admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get employee statistics
        $employeeStats = app(HomeController::class)->getEmployeeStatistics();
        
        // Debug: Check if $employeeStats is properly populated
        // \Log::info('Employee Stats:', $employeeStats);
        
        // Get user statistics - exclude admin users
        $totalUsers = User::where('isAdmin', false)->count();
        $activeUsers = User::where('isAdmin', false)
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', Carbon::now()->subDay())
            ->count();
        $inactiveUsers = $totalUsers - $activeUsers;
        
        // Get recent logins - exclude admin users
        $recentLogins = User::where('isAdmin', false)
            ->whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get record counts
        $familyPlanningCount = FamilyPlanning::count();
        $immunizationCount = \App\Models\ChildProfile::count();
        $totalRecords = $familyPlanningCount + $immunizationCount;

        // Get monthly and yearly records
        $monthlyRecords = [
            'familyPlanning' => FamilyPlanning::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get(),
            'immunization' => ImmunizationRecord::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
        ];
        
        $yearlyRecords = [
            'familyPlanning' => FamilyPlanning::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                ->groupBy('year')
                ->orderBy('year')
                ->get(),
            'immunization' => ImmunizationRecord::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                ->groupBy('year')
                ->orderBy('year')
                ->get()
        ];
        
        // Get filter options for dropdowns
        $puroks = FamilyPlanning::whereNotNull('purok')
            ->select('purok')
            ->distinct()
            ->pluck('purok')
            ->toArray();
            
        $fpMethods = FamilyPlanning::whereNotNull('fp_method')
            ->select('fp_method')
            ->distinct()
            ->pluck('fp_method')
            ->toArray();
            
        $intendedMethods = FamilyPlanning::whereNotNull('intended_method')
            ->select('intended_method')
            ->distinct()
            ->pluck('intended_method')
            ->toArray();
            
        $vaccineTypes = DB::table('vaccinations')
            ->select('vaccine_type')
            ->distinct()
            ->pluck('vaccine_type')
            ->toArray();
        
        return view('admin.dashboard', compact(
            'employeeStats',
            'totalUsers', 
            'activeUsers', 
            'inactiveUsers', 
            'recentLogins',
            'familyPlanningCount',
            'immunizationCount',
            'totalRecords',
            'puroks',
            'fpMethods',
            'intendedMethods',
            'vaccineTypes',
            'monthlyRecords',
            'yearlyRecords'
        ));
    }
    
    /**
     * Display user management view.
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        return redirect()->route('admin.user-activity');
    }

    /**
     * Show the user activity page
     */
    public function userActivity()
    {
        $activities = \App\Models\User::orderByDesc('last_login_at')->paginate(15);
return view('admin.user-activity', compact('activities'));
    }

    /**
     * Show the export reports page
     */
    public function exportReports()
    {
        return view('admin.export-reports');
    }
    
    /**
     * Display family planning records for admin tracking.
     *
     * @return \Illuminate\Http\Response
     */
    public function familyPlanningRecords()
    {
        $records = FamilyPlanning::with(['user', 'createdBy', 'edits'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return response(view('admin.family-planning-records', compact('records')));
    }
    
    /**
     * Display edit history for a specific family planning record.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function familyPlanningEditHistory($id)
    {
        $record = FamilyPlanning::findOrFail($id);
        $edits = FamilyPlanningEdit::where('family_planning_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response(view('admin.edit-history', compact('record', 'edits')));
    }
    
    /**
     * Get employee analytics data based on filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeAnalytics(Request $request)
    {
        // Get filter parameters
        $dateRange = $request->input('date_range', 30);
        
        // Build base query with filters
        $startDate = Carbon::now()->subDays((int)$dateRange);
        
        // User activity by day of week
        $activityByDay = DB::table('users')
            ->select(DB::raw('DAYOFWEEK(last_login_at) as day_of_week'), DB::raw('COUNT(DISTINCT id) as user_count'))
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', $startDate)
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();
        
        // Convert day of week number to name
        $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $activityData = [
            'labels' => [],
            'values' => array_fill(0, 7, 0)
        ];
        
        foreach ($activityByDay as $activity) {
            // DAYOFWEEK returns 1 for Sunday, 2 for Monday, etc.
            $dayIndex = $activity->day_of_week - 1;
            $activityData['values'][$dayIndex] = $activity->user_count;
        }
        $activityData['labels'] = $dayNames;
        
        // Login time distribution (morning, afternoon, evening, night)
        $loginTimeDistribution = DB::table('users')
            ->select(DB::raw('
                CASE
                    WHEN HOUR(last_login_at) BETWEEN 5 AND 11 THEN "Morning"
                    WHEN HOUR(last_login_at) BETWEEN 12 AND 16 THEN "Afternoon"
                    WHEN HOUR(last_login_at) BETWEEN 17 AND 20 THEN "Evening"
                    ELSE "Night"
                END as time_of_day
            '), DB::raw('COUNT(DISTINCT id) as user_count'))
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', $startDate)
            ->groupBy('time_of_day')
            ->get();
        
        $timeDistributionData = [
            'labels' => ['Morning', 'Afternoon', 'Evening', 'Night'],
            'values' => [0, 0, 0, 0]
        ];
        
        foreach ($loginTimeDistribution as $data) {
            $index = array_search($data->time_of_day, $timeDistributionData['labels']);
            if ($index !== false) {
                $timeDistributionData['values'][$index] = $data->user_count;
            }
        }
        
        // Generate description for charts
        $activityDescription = $this->generateActivityDescription($activityData);
        $timeDistributionDescription = $this->generateTimeDistributionDescription($timeDistributionData);
        
        return response()->json([
            'activityData' => $activityData,
            'timeDistributionData' => $timeDistributionData,
            'activityDescription' => $activityDescription,
            'timeDistributionDescription' => $timeDistributionDescription
        ]);
    }
    
    /**
     * Get family planning analytics data for admin dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminFamilyPlanningAnalytics(Request $request)
    {
        // Get filter parameters
        $purok = $request->input('purok');
        $method = $request->input('method');
        $dateRange = $request->input('date_range');
        
        // Build base query with filters
        $query = FamilyPlanning::query();
        
        if ($purok) {
            $query->where('purok', $purok);
        }
        
        if ($method) {
            $query->where('fp_method', $method);
        }
        
        if ($dateRange && $dateRange !== 'all') {
            $query->where('created_at', '>=', Carbon::now()->subDays((int)$dateRange));
        }
        
        // Total count for this filtered set
        $filteredCount = $query->count();
        
        // Get FP Method distribution
        $methodsData = $this->getFpMethodsDistribution($query->clone());
        
        // Get WRA vs NWRA distribution
        $wraData = $this->getWraDistribution($query->clone());
        
        // Get purok distribution
        $purokData = $this->getPurokDistribution($query->clone());
        
        // Get monthly data
        $monthlyData = $this->getMonthlyFpData($query->clone());
        
        // Prepare descriptions for charts
        $methodsDescription = $this->generateFpMethodsDescription($methodsData, $filteredCount);
        $wraDescription = $this->generateWraDescription($wraData, $filteredCount);
        $purokDescription = $this->generatePurokDescription($purokData, $filteredCount);
        $monthlyDescription = $this->generateMonthlyFpDescription($monthlyData);
        
        return response()->json([
            'methodsData' => $methodsData,
            'wraData' => $wraData,
            'purokData' => $purokData,
            'monthlyData' => $monthlyData,
            'methodsDescription' => $methodsDescription,
            'wraDescription' => $wraDescription,
            'purokDescription' => $purokDescription,
            'monthlyDescription' => $monthlyDescription
        ]);
    }
    
    /**
     * Get immunization analytics data for admin dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminImmunizationAnalytics(Request $request)
    {
        // Get filter parameters
        $purok = $request->input('purok');
        $vaccineType = $request->input('vaccine_type');
        $dateRange = $request->input('date_range');
        
        // Build base query with filters
        $query = ImmunizationRecord::query();
        
        if ($purok) {
            $query->where('purok', $purok);
        }
        
        $vaccineQuery = DB::table('vaccinations');
        
        if ($vaccineType) {
            $vaccineQuery->where('vaccine_type', $vaccineType);
        }
        
        if ($dateRange && $dateRange !== 'all') {
            $query->where('created_at', '>=', Carbon::now()->subDays((int)$dateRange));
            $vaccineQuery->where('created_at', '>=', Carbon::now()->subDays((int)$dateRange));
        }
        
        // Total count for this filtered set
        $filteredCount = $query->count();
        
        // Get vaccine types distribution
        $vaccineTypesData = $this->getVaccineTypesDistribution($vaccineQuery->clone());
        
        // Get vaccination completion status
        $completionData = $this->getVaccinationCompletionStatus($query->clone());
        
        // Get age group distribution
        $ageGroupData = $this->getImmunizationAgeGroups($query->clone());
        
        // Get monthly data
        $monthlyData = $this->getMonthlyImmunizationData($query->clone());
        
        // Prepare descriptions for charts
        $vaccineTypesDescription = $this->generateVaccineTypesDescription($vaccineTypesData);
        $completionDescription = $this->generateImmCompletionDescription($completionData, $filteredCount);
        $ageGroupDescription = $this->generateAgeGroupDescription($ageGroupData, $filteredCount);
        $monthlyDescription = $this->generateMonthlyImmDescription($monthlyData);
        
        return response()->json([
            'vaccineTypesData' => $vaccineTypesData,
            'completionData' => $completionData,
            'ageGroupData' => $ageGroupData,
            'monthlyData' => $monthlyData,
            'vaccineTypesDescription' => $vaccineTypesDescription,
            'completionDescription' => $completionDescription,
            'ageGroupDescription' => $ageGroupDescription,
            'monthlyDescription' => $monthlyDescription
        ]);
    }
    
    /**
     * Get monthly reports data for admin dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyReportsData()
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
        
        // Monthly FP records
        $monthlyFpData = FamilyPlanning::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        // Monthly immunization records
        $monthlyImmData = ImmunizationRecord::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Prepare data for chart
        $labels = [];
        $fpValues = array_fill(0, 12, 0);
        $immValues = array_fill(0, 12, 0);
        
        // Month names for labels
        $monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
        
        for ($i = 0; $i < 12; $i++) {
            $labels[] = $monthNames[$i];
            
            // Find FP data for this month
            $fpData = $monthlyFpData->first(function($item) use ($i) {
                return $item->month == ($i + 1);
            });
            
            if ($fpData) {
                $fpValues[$i] = $fpData->count;
            }
            
            // Find immunization data for this month
            $immData = $monthlyImmData->first(function($item) use ($i) {
                return $item->month == ($i + 1);
            });
            
            if ($immData) {
                $immValues[$i] = $immData->count;
            }
        }
        
        $reportsData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Family Planning Records',
                    'data' => $fpValues,
                    'backgroundColor' => 'rgba(78, 115, 223, 0.2)',
                    'borderColor' => 'rgba(78, 115, 223, 1)'
                ],
                [
                    'label' => 'Immunization Records',
                    'data' => $immValues,
                    'backgroundColor' => 'rgba(28, 200, 138, 0.2)',
                    'borderColor' => 'rgba(28, 200, 138, 1)'
                ]
            ]
        ];
        
        $reportsDescription = $this->generateMonthlyReportsDescription($fpValues, $immValues);
        
        return response()->json([
            'reportsData' => $reportsData,
            'reportsDescription' => $reportsDescription
        ]);
    }
    
    /**
     * Generate description for user activity chart
     */
    private function generateActivityDescription($data)
    {
        $totalActivity = array_sum($data['values']);
        if ($totalActivity === 0) {
            return "<p class='text-muted'>No user activity data available for the selected time period.</p>";
        }
        
        // Find the most active day
        $maxIndex = array_search(max($data['values']), $data['values']);
        $mostActiveDay = $data['labels'][$maxIndex];
        $mostActiveDayCount = $data['values'][$maxIndex];
        
        // Find the least active day
        $minIndex = array_search(min($data['values']), $data['values']);
        $leastActiveDay = $data['labels'][$minIndex];
        $leastActiveDayCount = $data['values'][$minIndex];
        
        return "<p>Based on user login activity, <strong>{$mostActiveDay}</strong> is the most active day with {$mostActiveDayCount} active users, while <strong>{$leastActiveDay}</strong> is the least active with {$leastActiveDayCount} active users.</p>";
    }
    
    /**
     * Generate description for login time distribution chart
     */
    private function generateTimeDistributionDescription($data)
    {
        $totalLogins = array_sum($data['values']);
        if ($totalLogins === 0) {
            return "<p class='text-muted'>No login time data available for the selected time period.</p>";
        }
        
        // Find the most common login time
        $maxIndex = array_search(max($data['values']), $data['values']);
        $mostCommonTime = $data['labels'][$maxIndex];
        $mostCommonTimeCount = $data['values'][$maxIndex];
        $mostCommonTimePercentage = round(($mostCommonTimeCount / $totalLogins) * 100, 1);
        
        return "<p>Most health workers ({$mostCommonTimeCount}, {$mostCommonTimePercentage}%) tend to log in during the <strong>{$mostCommonTime}</strong> hours.</p>";
    }
    
    /**
     * Generate FP methods distribution data
     */
    private function getFpMethodsDistribution($query)
    {
        $methods = $query->whereNotNull('fp_method')
            ->select('fp_method', DB::raw('count(*) as count'))
            ->groupBy('fp_method')
            ->get();
            
        $labels = $methods->pluck('fp_method')->toArray();
        $values = $methods->pluck('count')->toArray();
        
        return ['labels' => $labels, 'values' => $values];
    }
    
    /**
     * Generate WRA vs NWRA distribution data
     */
    private function getWraDistribution($query)
    {
        $total = $query->count();
        
        $wraCount = $query->whereNotNull('birthdate')
            ->get()
            ->filter(function ($record) {
                $age = Carbon::parse($record->birthdate)->age;
                return $record->sex === 'Female' && $age >= 15 && $age <= 49;
            })
            ->count();
            
        $nwraCount = $total - $wraCount;
        
        return [
            'labels' => ['WRA (15-49 years)', 'NWRA'],
            'values' => [$wraCount, $nwraCount]
        ];
    }
    
    /**
     * Generate purok distribution data
     */
    private function getPurokDistribution($query)
    {
        $puroks = $query->whereNotNull('purok')
            ->select('purok', DB::raw('count(*) as count'))
            ->groupBy('purok')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
            
        $labels = $puroks->pluck('purok')->toArray();
        $values = $puroks->pluck('count')->toArray();
        
        return ['labels' => $labels, 'values' => $values];
    }
    
    /**
     * Generate monthly FP data
     */
    private function getMonthlyFpData($query)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        
        $monthlyData = $query
            ->where('created_at', '>=', $sixMonthsAgo)
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $labels = [];
        $values = [];
        
        // Prepare all months in the range
        $period = new \DatePeriod(
            $sixMonthsAgo,
            new \DateInterval('P1M'),
            Carbon::now()->endOfMonth()
        );
        
        foreach ($period as $date) {
            $monthKey = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            
            $monthCount = $monthlyData
                ->where('year', $date->year)
                ->where('month', $date->month)
                ->first();
                
            $values[] = $monthCount ? $monthCount->count : 0;
        }
        
        return ['labels' => $labels, 'values' => $values];
    }
    
    /**
     * Generate vaccine types distribution data
     */
    private function getVaccineTypesDistribution($query)
    {
        $vaccineTypes = $query
            ->select('vaccine_type', DB::raw('count(*) as count'))
            ->groupBy('vaccine_type')
            ->get();
            
        $labels = $vaccineTypes->pluck('vaccine_type')->toArray();
        $values = $vaccineTypes->pluck('count')->toArray();
        
        return ['labels' => $labels, 'values' => $values];
    }
    
    /**
     * Generate vaccination completion status data
     */
    private function getVaccinationCompletionStatus($query)
    {
        $statuses = [
            'Completed' => 0,
            'In Progress' => 0,
            'Not Started' => 0
        ];
        
        $records = $query->get();
        
        foreach ($records as $record) {
            $status = $record->getCompletionStatus(); // This method should be defined in your ImmunizationRecord model
            if (isset($statuses[$status])) {
                $statuses[$status]++;
            }
        }
        
        return [
            'labels' => array_keys($statuses),
            'values' => array_values($statuses)
        ];
    }
    
    /**
     * Generate immunization age groups data
     */
    private function getImmunizationAgeGroups($query)
    {
        $ageGroups = [
            '0-1 mo' => 0,
            '2-3 mo' => 0,
            '4-6 mo' => 0,
            '7-9 mo' => 0,
            '10-12 mo' => 0,
            '1-2 yr' => 0,
            '2+ yr' => 0
        ];
        
        $records = $query->whereNotNull('birthdate')->get();
        
        foreach ($records as $record) {
            $ageInMonths = Carbon::parse($record->birthdate)->diffInMonths(Carbon::now());
            
            if ($ageInMonths <= 1) {
                $ageGroups['0-1 mo']++;
            } elseif ($ageInMonths <= 3) {
                $ageGroups['2-3 mo']++;
            } elseif ($ageInMonths <= 6) {
                $ageGroups['4-6 mo']++;
            } elseif ($ageInMonths <= 9) {
                $ageGroups['7-9 mo']++;
            } elseif ($ageInMonths <= 12) {
                $ageGroups['10-12 mo']++;
            } elseif ($ageInMonths <= 24) {
                $ageGroups['1-2 yr']++;
            } else {
                $ageGroups['2+ yr']++;
            }
        }
        
        return [
            'labels' => array_keys($ageGroups),
            'values' => array_values($ageGroups)
        ];
    }
    
    /**
     * Generate monthly immunization data
     */
    private function getMonthlyImmunizationData($query)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        
        $monthlyData = $query
            ->where('created_at', '>=', $sixMonthsAgo)
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        return $monthlyData;
    }
    
    /**
     * Generate descriptive text for FP methods chart
     */
    private function generateFpMethodsDescription($data, $total)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        // Find the most and least common methods
        $values = array_combine($data['labels'], $data['values']);
        arsort($values);
        
        $mostCommon = key($values);
        $mostCommonCount = current($values);
        $mostCommonPercentage = $total > 0 ? round(($mostCommonCount / $total) * 100, 1) : 0;
        
        end($values);
        $leastCommon = key($values);
        $leastCommonCount = current($values);
        $leastCommonPercentage = $total > 0 ? round(($leastCommonCount / $total) * 100, 1) : 0;
        
        return "<p>Based on the selected filters, <strong>{$mostCommon}</strong> is the most commonly used family planning method with {$mostCommonCount} records ({$mostCommonPercentage}% of total). <strong>{$leastCommon}</strong> is the least used method with {$leastCommonCount} records ({$leastCommonPercentage}% of total).</p>";
    }
    
    /**
     * Generate descriptive text for WRA vs NWRA chart
     */
    private function generateWraDescription($data, $total)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        $wraCount = $data['values'][0];
        $wraPercentage = $total > 0 ? round(($wraCount / $total) * 100, 1) : 0;
        
        return "<p>Based on the selected filters, <strong>{$wraCount}</strong> ({$wraPercentage}%) of the records are from Women of Reproductive Age (15-49 years).</p>";
    }
    
    /**
     * Generate descriptive text for purok distribution chart
     */
    private function generatePurokDescription($data, $total)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        // Find the purok with highest records
        $maxIndex = array_search(max($data['values']), $data['values']);
        $topPurok = $data['labels'][$maxIndex];
        $topPurokCount = $data['values'][$maxIndex];
        $topPurokPercentage = $total > 0 ? round(($topPurokCount / $total) * 100, 1) : 0;
        
        return "<p>The purok with the highest number of records is <strong>{$topPurok}</strong> with {$topPurokCount} records ({$topPurokPercentage}% of total).</p>";
    }
    
    /**
     * Generate descriptive text for monthly FP chart
     */
    private function generateMonthlyFpDescription($data)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        // Find the month with highest records
        $maxIndex = array_search(max($data['values']), $data['values']);
        $maxMonth = $data['labels'][$maxIndex];
        $maxCount = $data['values'][$maxIndex];
        
        // Calculate trend (increase/decrease) between first and last month
        $firstMonth = $data['values'][0];
        $lastMonth = end($data['values']);
        $trend = $firstMonth > 0 ? round((($lastMonth - $firstMonth) / $firstMonth) * 100, 1) : 0;
        
        $trendText = $trend > 0 ? "an increase of {$trend}%" : "a decrease of " . abs($trend) . "%";
        if ($trend == 0) $trendText = "no change";
        
        return "<p>The highest number of records ({$maxCount}) was recorded in <strong>{$maxMonth}</strong>. Comparing the first and last months of this period shows {$trendText}.</p>";
    }
    
    /**
     * Generate descriptive text for vaccine types chart
     */
    private function generateVaccineTypesDescription($data)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        // Find the most administered vaccine
        $maxIndex = array_search(max($data['values']), $data['values']);
        $maxVaccine = $data['labels'][$maxIndex];
        $maxCount = $data['values'][$maxIndex];
        
        $totalVaccines = array_sum($data['values']);
        $maxPercentage = $totalVaccines > 0 ? round(($maxCount / $totalVaccines) * 100, 1) : 0;
        
        return "<p>A total of <strong>{$totalVaccines}</strong> vaccines have been administered. The most common vaccine is <strong>{$maxVaccine}</strong> with {$maxCount} doses ({$maxPercentage}% of total).</p>";
    }
    
    /**
     * Generate descriptive text for immunization completion chart
     */
    private function generateImmCompletionDescription($data, $total)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        $completedIndex = array_search('Completed', $data['labels']);
        $completedCount = $data['values'][$completedIndex];
        $completedPercentage = $total > 0 ? round(($completedCount / $total) * 100, 1) : 0;
        
        return "<p>Out of {$total} immunization records, <strong>{$completedCount}</strong> ({$completedPercentage}%) have completed all required vaccinations for their age group.</p>";
    }
    
    /**
     * Generate descriptive text for age group chart
     */
    private function generateAgeGroupDescription($data, $total)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        // Find the largest age group
        $maxIndex = array_search(max($data['values']), $data['values']);
        $maxAgeGroup = $data['labels'][$maxIndex];
        $maxCount = $data['values'][$maxIndex];
        $maxPercentage = $total > 0 ? round(($maxCount / $total) * 100, 1) : 0;
        
        return "<p>The largest age group in the immunization records is <strong>{$maxAgeGroup}</strong> with {$maxCount} children ({$maxPercentage}% of total).</p>";
    }
    
    /**
     * Generate descriptive text for monthly immunization chart
     */
    private function generateMonthlyImmDescription($data)
    {
        if (empty($data['values']) || array_sum($data['values']) === 0) {
            return "<p class='text-muted'>No data available for the selected filters.</p>";
        }
        
        // Find the month with highest records
        $maxIndex = array_search(max($data['values']), $data['values']);
        $maxMonth = $data['labels'][$maxIndex];
        $maxCount = $data['values'][$maxIndex];
        
        // Calculate total immunizations in the period
        $totalImm = array_sum($data['values']);
        $avgPerMonth = round($totalImm / count($data['values']), 1);
        
        return "<p>A total of <strong>{$totalImm}</strong> immunizations were recorded in this period, with an average of {$avgPerMonth} per month. The highest number ({$maxCount}) was in <strong>{$maxMonth}</strong>.</p>";
    }
    
    /**
     * Generate descriptive text for monthly reports chart
     */
    private function generateMonthlyReportsDescription($fpValues, $immValues)
    {
        $totalFp = array_sum($fpValues);
        $totalImm = array_sum($immValues);
        
        if ($totalFp === 0 && $totalImm === 0) {
            return "<p class='text-muted'>No records available for the current year.</p>";
        }
        
        // Find the months with highest records
        $maxFpIndex = array_search(max($fpValues), $fpValues);
        $maxFpMonth = $maxFpIndex !== false ? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][$maxFpIndex] : 'N/A';
        $maxFpCount = $maxFpIndex !== false ? $fpValues[$maxFpIndex] : 0;
        
        $maxImmIndex = array_search(max($immValues), $immValues);
        $maxImmMonth = $maxImmIndex !== false ? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][$maxImmIndex] : 'N/A';
        $maxImmCount = $maxImmIndex !== false ? $immValues[$maxImmIndex] : 0;
        
        return "<p>For the current year, a total of <strong>{$totalFp}</strong> family planning and <strong>{$totalImm}</strong> immunization records have been created. The highest number of family planning records ({$maxFpCount}) was in <strong>{$maxFpMonth}</strong>, while the highest number of immunization records ({$maxImmCount}) was in <strong>{$maxImmMonth}</strong>.</p>";
    }
    
    /**
     * Get user reports analytics data based on filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserReportsAnalytics(Request $request)
    {
        // Get filter parameters
        $reportType = $request->input('report_type');
        $userId = $request->input('user_id');
        $dateRange = $request->input('date_range', 30);
        
        // Set the date range
        $startDate = Carbon::now()->subDays((int)$dateRange);
        
        // Build base queries for both report types
        $fpQuery = FamilyPlanning::with('user')
            ->where('created_at', '>=', $startDate);
            
        $immunizationQuery = ImmunizationRecord::with('user')
            ->where('created_at', '>=', $startDate);
        
        // Apply user filter if provided
        if ($userId) {
            $fpQuery->where('user_id', $userId);
            $immunizationQuery->where('user_id', $userId);
        }
        
        // Get report counts based on type filter
        if ($reportType == 'family_planning') {
            $fpReports = $fpQuery->get();
            $immunizationReports = collect();
        } elseif ($reportType == 'immunization') {
            $fpReports = collect();
            $immunizationReports = $immunizationQuery->get();
        } else {
            $fpReports = $fpQuery->get();
            $immunizationReports = $immunizationQuery->get();
        }
        
        // Report type distribution data
        $typeData = [
            'labels' => ['Family Planning', 'Immunization'],
            'values' => [$fpReports->count(), $immunizationReports->count()]
        ];
        
        // Top contributors data
        $contributorsData = $this->getTopContributors($fpReports, $immunizationReports);
        
        // Daily reports data
        $dailyData = $this->getDailyReportsData($fpReports, $immunizationReports);
        
        // Completion status data
        $completionData = $this->getReportsCompletionData($fpReports, $immunizationReports);
        
        // Recent reports data
        $recentReports = $this->getRecentReportsData($fpReports, $immunizationReports);
        
        return response()->json([
            'typeData' => $typeData,
            'contributorsData' => $contributorsData,
            'dailyData' => $dailyData,
            'completionData' => $completionData,
            'recentReports' => $recentReports
        ]);
    }
    
    /**
     * Get top contributors data
     */
    private function getTopContributors($fpReports, $immunizationReports)
    {
        // Combine both report types
        $allReports = $fpReports->concat($immunizationReports);
        
        // Group by user and count
        $userCounts = [];
        foreach ($allReports as $report) {
            if (!$report->user) continue;
            
            $userId = $report->user->id;
            $userName = $report->user->first_name . ' ' . $report->user->last_name;
            
            if (!isset($userCounts[$userId])) {
                $userCounts[$userId] = [
                    'name' => $userName,
                    'count' => 0
                ];
            }
            
            $userCounts[$userId]['count']++;
        }
        
        // Sort by count in descending order
        uasort($userCounts, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        // Take top 5
        $topContributors = array_slice($userCounts, 0, 5);
        
        return [
            'labels' => array_column($topContributors, 'name'),
            'values' => array_column($topContributors, 'count')
        ];
    }
    
    /**
     * Get daily reports data
     */
    private function getDailyReportsData($fpReports, $immunizationReports)
    {
        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $dailyCounts = array_fill(0, 7, 0);
        
        // Combine both report types
        $allReports = $fpReports->concat($immunizationReports);
        
        // Group by day of week
        foreach ($allReports as $report) {
            $dayOfWeek = Carbon::parse($report->created_at)->dayOfWeekIso - 1; // 1 (Mon) to 7 (Sun)
            $dailyCounts[$dayOfWeek]++;
        }
        
        return [
            'labels' => $daysOfWeek,
            'values' => $dailyCounts
        ];
    }
    
    /**
     * Get reports completion status data
     */
    private function getReportsCompletionData($fpReports, $immunizationReports)
    {
        $statuses = [
            'Complete' => 0,
            'Partially Complete' => 0,
            'Incomplete' => 0
        ];
        
        // Process family planning reports
        foreach ($fpReports as $report) {
            $status = $report->getCompletionStatus();
            
            if ($status === 'Complete') {
                $statuses['Complete']++;
            } elseif ($status === 'Partially Complete') {
                $statuses['Partially Complete']++;
            } else {
                $statuses['Incomplete']++;
            }
        }
        
        // Process immunization reports
        foreach ($immunizationReports as $report) {
            $status = $report->getCompletionStatus();
            
            if ($status === 'Complete') {
                $statuses['Complete']++;
            } elseif ($status === 'Partially Complete') {
                $statuses['Partially Complete']++;
            } else {
                $statuses['Incomplete']++;
            }
        }
        
        return [
            'labels' => array_keys($statuses),
            'values' => array_values($statuses)
        ];
    }
    
    /**
     * Get recent reports data
     */
    private function getRecentReportsData($fpReports, $immunizationReports)
    {
        // Combine both report types and add type information
        $fpReportsWithType = $fpReports->map(function ($report) {
            return [
                'id' => $report->id,
                'type' => 'Family Planning',
                'user' => $report->user ? $report->user->first_name . ' ' . $report->user->last_name : 'Unknown',
                'name' => $report->first_name . ' ' . $report->last_name,
                'date' => $report->created_at->format('Y-m-d'),
                'status' => $report->getCompletionStatus()
            ];
        });
        
        $immunizationReportsWithType = $immunizationReports->map(function ($report) {
            return [
                'id' => $report->id,
                'type' => 'Immunization',
                'user' => $report->user ? $report->user->first_name . ' ' . $report->user->last_name : 'Unknown',
                'name' => $report->child_name ?? ($report->first_name . ' ' . $report->last_name),
                'date' => $report->created_at->format('Y-m-d'),
                'status' => $report->getCompletionStatus()
            ];
        });
        
        // Combine and sort by date (most recent first)
        $allReports = $fpReportsWithType->concat($immunizationReportsWithType)
            ->sortByDesc('date')
            ->take(10)
            ->values()
            ->toArray();
            
        return $allReports;
    }

    /**
     * Get user analytics data for admin dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAnalytics(Request $request)
    {
        // Get filter parameters
        $dateRange = $request->input('date_range', 30);
        
        // Set date filter
        $startDate = ($dateRange === 'all') 
            ? null 
            : Carbon::now()->subDays((int)$dateRange);
        
        // Build base query
        $query = User::query();
        
        if ($startDate) {
            $query->where(function($q) use ($startDate) {
                $q->where('last_login_at', '>=', $startDate)
                  ->orWhere('created_at', '>=', $startDate);
            });
        }
        
        // Get all users matching the date filter
        $users = $query->get();
        
        // Active vs inactive users data
        $activeUsers = $users->filter(function($user) {
            return $user->last_login_at && $user->last_login_at >= Carbon::now()->subDay();
        })->count();
        
        $inactiveUsers = $users->count() - $activeUsers;
        
        $activityData = [
            'labels' => ['Active Users', 'Inactive Users'],
            'values' => [$activeUsers, $inactiveUsers]
        ];
        
        $activityDescription = "<p>Out of {$users->count()} total health workers, <strong>{$activeUsers}</strong> have been active in the last 24 hours.</p>";
        
        // Top users data (users with most logins)
        $topUsers = $users->sortByDesc(function($user) {
            // Count login activity - consider both login count and records created
            $loginWeight = isset($user->login_count) ? $user->login_count : 0;
            $recordsWeight = $this->getUserRecordsCount($user->id);
            
            return ($loginWeight * 0.7) + ($recordsWeight * 0.3); // Weighted score
        })->take(5);
        
        $topUsersData = [
            'labels' => $topUsers->map(function($user) {
                return $user->first_name . ' ' . $user->last_name;
            })->toArray(),
            'values' => $topUsers->map(function($user) {
                return isset($user->login_count) ? $user->login_count : 0;
            })->toArray()
        ];
        
        $mostActiveUser = $topUsers->first();
        $topUsersDescription = $mostActiveUser 
            ? "<p>The most active user is <strong>{$mostActiveUser->first_name} {$mostActiveUser->last_name}</strong> with " . (isset($mostActiveUser->login_count) ? $mostActiveUser->login_count : 0) . " logins.</p>" 
            : "<p>No user activity data available.</p>";
        
        // Login activity by day of week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $loginsByDay = array_fill(0, 7, 0);
        
        foreach ($users as $user) {
            if ($user->last_login_at) {
                $dayOfWeek = $user->last_login_at->dayOfWeekIso - 1; // 1 (Mon) to 7 (Sun), convert to 0-6
                $loginsByDay[$dayOfWeek]++;
            }
        }
        
        $loginActivityData = [
            'labels' => $daysOfWeek,
            'values' => $loginsByDay
        ];
        
        // Find the most active day
        $maxValue = max($loginsByDay);
        $maxDay = $daysOfWeek[array_search($maxValue, $loginsByDay)];
        
        $loginActivityDescription = "<p>Most user activity occurs on <strong>{$maxDay}</strong> with {$maxValue} logins.</p>";
        
        // Recent user activity data for table
        $recentActivity = $users->sortByDesc('last_login_at')
            ->take(10)
            ->map(function($user) {
                return [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'last_login' => $user->last_login_at ? $user->last_login_at->toDateTimeString() : null,
                    'reports_count' => $this->getUserRecordsCount($user->id),
                    'status' => $this->getUserStatus($user)
                ];
            })->toArray();
        
        return response()->json([
            'activityData' => $activityData,
            'topUsersData' => $topUsersData,
            'loginActivityData' => $loginActivityData,
            'activityDescription' => $activityDescription,
            'topUsersDescription' => $topUsersDescription,
            'loginActivityDescription' => $loginActivityDescription,
            'recentActivity' => $recentActivity
        ]);
    }

    /**
     * Get count of records created by a user
     * 
     * @param int $userId
     * @return int
     */
    private function getUserRecordsCount($userId)
    {
        $fpCount = FamilyPlanning::where('user_id', $userId)->count();
        $immCount = ImmunizationRecord::where('user_id', $userId)->count();
        
        return $fpCount + $immCount;
    }

    /**
     * Get user online status
     * 
     * @param \App\Models\User $user
     * @return string
     */
    private function getUserStatus($user)
    {
        if (!$user->last_login_at) {
            return 'Offline';
        }
        
        if ($user->last_logout_at && $user->last_logout_at > $user->last_login_at) {
            return 'Offline';
        }
        
        if ($user->last_login_at >= Carbon::now()->subHours(1)) {
            return 'Online';
        }
        
        return 'Offline';
    }
}
