<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChildProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ChildProfile::query();

        // Apply filters
        if ($request->filled('purok')) {
            $query->filterByPurok($request->purok);
        }

        if ($request->filled('barangay')) {
            $query->filterByBarangay($request->barangay);
        }

        if ($request->filled('city')) {
            $query->filterByCity($request->city);
        }
        
        if ($request->filled('search')) {
            $query->filterByNameSearch($request->search);
        }

        // Get unique values for filter dropdowns
        $puroks = ChildProfile::distinct()->pluck('purok')->sort();
        $barangays = ChildProfile::distinct()->pluck('barangay')->sort();
        $cities = ChildProfile::distinct()->pluck('city')->sort();

        $children = $query->with('vaccinations')->latest()->paginate(10);
        \Log::info('Child profiles fetched: ' . $children->count());
        \Log::info('Vaccinations fetched: ' . $children->sum(function($child) { return $child->vaccinations->count(); }));

        return view('immunization.index', compact(
            'children',
            'puroks',
            'barangays',
            'cities'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('immunization.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'house_lot_no' => 'required|string|max:255',
            'purok' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'mothers_name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'birth_weight' => 'required|numeric|min:0',
            'birth_height' => 'required|numeric|min:0',
        ]);

        $validated['created_by_user_id'] = Auth::id();
        
        $child = ChildProfile::create($validated);
        
        // Create initial vaccination records with scheduled statuses
        $vaccineTypes = [
            'BCG' => 1,
            'Hepatitis B' => 1,
            'Pentavalent Vaccine' => 3,
            'Oral Polio Vaccine' => 3,
            'Inactivated Polio Vaccine' => 2,
            'Pneumococcal Conjugate Vaccine' => 3,
            'Measles,Mumps,&Rubella' => 2
        ];
        
        foreach ($vaccineTypes as $vaccineType => $doses) {
            for ($dose = 1; $dose <= $doses; $dose++) {
                // Create a new vaccination record
                $vaccination = new Vaccination();
                $vaccination->child_id = $child->id;
                $vaccination->vaccine_type = $vaccineType;
                $vaccination->dose_number = $dose;
                $vaccination->status = 'Not Completed';
                
                // Calculate expected vaccination date
                if ($vaccination->expected_age !== null) {
                    $expectedDate = clone $child->birthdate;
                    $expectedDate->addMonths(floor($vaccination->expected_age));
                    $expectedDate->addDays(round(($vaccination->expected_age - floor($vaccination->expected_age)) * 30));
                    
                    $vaccination->next_schedule = $expectedDate;
                }
                
                $vaccination->save();
            }
        }

        return redirect()->route('immunization.show', $child->id)
            ->with('success', 'Child profile and vaccination schedule created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChildProfile  $child
     * @return \Illuminate\Http\Response
     */
    public function show(ChildProfile $child)
    {
        $vaccines = [
            'BCG' => 1,
            'Hepatitis B' => 1,
            'Pentavalent Vaccine' => 3,
            'Oral Polio Vaccine' => 3,
            'Inactivated Polio Vaccine' => 2,
            'Pneumococcal Conjugate Vaccine' => 3,
            'Measles,Mumps,&Rubella' => 2
        ];
        
        // Group vaccinations by type
        $vaccinations = [];
        foreach ($vaccines as $vaccineType => $requiredDoses) {
            $vaccinations[$vaccineType] = [];
            
            // Get existing vaccinations
            $existingVaccinations = $child->vaccinations()
                ->where('vaccine_type', $vaccineType)
                ->orderBy('dose_number')
                ->get();
                
            // Organize by dose number
            foreach ($existingVaccinations as $vaccination) {
                $vaccinations[$vaccineType][$vaccination->dose_number] = $vaccination;
            }
        }
        
        return view('immunization.show', compact('child', 'vaccinations', 'vaccines'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChildProfile  $child
     * @return \Illuminate\Http\Response
     */
    public function edit(ChildProfile $child)
    {
        // The view is expecting $immunizationRecord but we're passing $child
        // Let's check if we're trying to edit a vaccination or the child profile
        $requestPath = request()->path();
        if (strpos($requestPath, 'vaccination') !== false) {
            // If we're editing a specific vaccination, we need to get it
            $vaccinationId = intval(basename(request()->url()));
            if ($vaccinationId > 0) {
                $vaccination = Vaccination::findOrFail($vaccinationId);
                return view('immunization.edit_vaccination', compact('child', 'vaccination'));
            }
        }
        
        return view('immunization.edit', compact('child'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChildProfile  $child
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChildProfile $child)
    {
        $validated = $request->validate([
            'house_lot_no' => 'required|string|max:255',
            'purok' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'mothers_name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'birth_weight' => 'required|numeric|min:0',
            'birth_height' => 'required|numeric|min:0',
        ]);

        $child->update($validated);

        return redirect()->route('immunization.index')
            ->with('success', 'Child profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChildProfile  $child
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChildProfile $child)
    {
        $child->delete();

        return redirect()->route('immunization.index')
            ->with('success', 'Child profile and vaccination records deleted successfully.');
    }
    
    /**
     * Dashboard statistics for immunization
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Get child count
        $totalChildren = ChildProfile::count();
        
        // Get vaccination statistics
        $totalVaccinations = Vaccination::where('status', 'Completed')->count();
        
        // Count children by vaccination status
        $fullyVaccinated = 0;
        $partiallyVaccinated = 0;
        $notVaccinated = 0;
        
        $childProfiles = ChildProfile::all();
        foreach ($childProfiles as $child) {
            $status = $child->vaccination_status['status'];
            if ($status === 'Fully Vaccinated') {
                $fullyVaccinated++;
            } elseif ($status === 'Partially Vaccinated') {
                $partiallyVaccinated++;
            } else {
                $notVaccinated++;
            }
        }
        
        // Get vaccination breakdown by type
        $vaccineTypes = [
            'BCG' => 1,
            'Hepatitis B' => 1,
            'Pentavalent Vaccine' => 3,
            'Oral Polio Vaccine' => 3,
            'Inactivated Polio Vaccine' => 2,
            'Pneumococcal Conjugate Vaccine' => 3,
            'Measles,Mumps,&Rubella' => 2
        ];
        
        $vaccineStats = [];
        foreach ($vaccineTypes as $vaccineType => $doses) {
            $totalCompleted = Vaccination::where('vaccine_type', $vaccineType)
                ->where('status', 'Completed')
                ->count();
                
            $vaccineStats[$vaccineType] = [
                'total_completed' => $totalCompleted,
                'total_possible' => $totalChildren * $doses,
                'percent_complete' => $totalChildren > 0 ? round(($totalCompleted / ($totalChildren * $doses)) * 100) : 0
            ];
        }
        
        // Get vaccination statistics by purok
        $purokStats = [];
        $puroks = ChildProfile::distinct()->pluck('purok')->sort();
        
        foreach ($puroks as $purok) {
            $childrenInPurok = ChildProfile::where('purok', $purok)->get();
            $childCount = $childrenInPurok->count();
            
            $purokFullyVaccinated = 0;
            $purokPartiallyVaccinated = 0;
            
            foreach ($childrenInPurok as $child) {
                $status = $child->vaccination_status['status'];
                if ($status === 'Fully Vaccinated') {
                    $purokFullyVaccinated++;
                } elseif ($status === 'Partially Vaccinated') {
                    $purokPartiallyVaccinated++;
                }
            }
            
            $purokStats[$purok] = [
                'total_children' => $childCount,
                'fully_vaccinated' => $purokFullyVaccinated,
                'partially_vaccinated' => $purokPartiallyVaccinated,
                'percent_fully' => $childCount > 0 ? round(($purokFullyVaccinated / $childCount) * 100) : 0,
                'percent_partially' => $childCount > 0 ? round(($purokPartiallyVaccinated / $childCount) * 100) : 0,
            ];
        }
        
        return view('immunization.dashboard', compact(
            'totalChildren',
            'totalVaccinations',
            'fullyVaccinated',
            'partiallyVaccinated',
            'notVaccinated',
            'vaccineStats',
            'purokStats'
        ));
    }

    /**
     * Export Immunization records to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function exportImmunizationExcel()
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

        // List of date columns to format
        $dateColumns = ['birthdate'];
        $numericColumns = ['birth_weight', 'birth_height'];

        $callback = function() use ($records, $columns, $dateColumns, $numericColumns) {
            $file = fopen('php://output', 'w');
            // Header row
            fputcsv($file, $columns);
            foreach ($records as $record) {
                $row = [];
                foreach ($columns as $col) {
                    $value = $record->$col;
                    // Format date columns as YYYY-MM-DD and prefix with tab for Excel readability
                    if (in_array($col, $dateColumns) && !empty($value)) {
                        try {
                            $date = $value instanceof \Carbon\Carbon ? $value : \Carbon\Carbon::parse($value);
                            $value = $date->format('Y-m-d');
                        } catch (\Exception $e) {}
                        $value = "\t" . $value;
                    }
                    // For all numeric values, prefix with tab to force Excel to treat as text
                    elseif ((in_array($col, $numericColumns) || is_numeric($value)) && !empty($value)) {
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
}