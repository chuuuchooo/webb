<?php

namespace App\Http\Controllers;

use App\Models\FamilyPlanning;
use App\Models\FamilyPlanningEdit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyPlanningController extends Controller
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
        $query = FamilyPlanning::query();

        // Search by name
        if ($request->filled('search')) {
            $query->searchName($request->search);
        }

        // Apply filters
        if ($request->filled('fp_method')) {
            $query->filterByFPMethod($request->fp_method);
        }

        if ($request->filled('provider')) {
            $query->filterByProvider($request->provider);
        }

        if ($request->filled('remarks')) {
            $query->filterByRemarks($request->remarks);
        }

        if ($request->filled('date_added')) {
            $query->filterByDateAdded($request->date_added);
        }

        if ($request->filled('sex')) {
            $query->filterBySex($request->sex);
        }

        if ($request->filled('purok')) {
            $query->filterByPurok($request->purok);
        }

        // Get unique values for filter dropdowns
        $barangays = FamilyPlanning::distinct()->pluck('barangay')->sort();
        $fpMethods = FamilyPlanning::distinct()->pluck('fp_method')->sort();
        $providers = FamilyPlanning::distinct()->pluck('provider_name')->sort();
        $remarks = FamilyPlanning::distinct()->pluck('remarks')->sort();
        $puroks = FamilyPlanning::distinct()->pluck('purok')->sort();
        $intendedMethods = FamilyPlanning::distinct()->pluck('intended_method')->sort();

        $familyPlannings = $query->latest()->paginate(10);

        return view('family-planning.index', compact(
            'familyPlannings',
            'fpMethods',
            'providers',
            'remarks',
            'puroks',
            'intendedMethods'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(view('family-planning.create'));
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
            'contact_number' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'intended_method' => 'required|string|max:255',
            'date_served' => 'required|date',
            'fp_method' => 'required|string|max:255',
            'provider_category' => 'required|string|max:255',
            'provider_name' => 'required|string|max:255',
            'mode_of_service_delivery' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
            'date_counselled_pregnant' => 'nullable|date',
            'other_notes' => 'nullable|string',
            'date_encoded' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        // Record creator
        $validated['created_by_user_id'] = auth()->id();

        FamilyPlanning::create($validated);

        return redirect()->route('family-planning.index')
            ->with('success', 'Family planning record created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilyPlanning  $familyPlanning
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyPlanning $familyPlanning)
    {
        return view('family-planning.show', compact('familyPlanning'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilyPlanning  $familyPlanning
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyPlanning $familyPlanning)
    {
        return view('family-planning.edit', compact('familyPlanning'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FamilyPlanning  $familyPlanning
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FamilyPlanning $familyPlanning)
    {
        \Log::info('Update request received', ['data' => $request->all(), 'record_id' => $familyPlanning->id]);
        
        try {
            // Validate the request data
            $validated = $request->validate([
                'house_lot_no' => 'required|string|max:255',
                'purok' => 'required|string|max:255',
                'barangay' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'contact_number' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'birthdate' => 'required|date',
                'sex' => 'required|string|in:Male,Female',
                'intended_method' => 'required|string|max:255',
                'date_served' => 'required|date',
                'fp_method' => 'required|string|max:255',
                'provider_category' => 'required|string|max:255',
                'provider_name' => 'required|string|max:255',
                'mode_of_service_delivery' => 'required|string|max:255',
                'remarks' => 'required|string|max:255',
                'date_counselled_pregnant' => 'nullable|date',
                'other_notes' => 'nullable|string',
                'date_encoded' => 'required|date',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            // Track changes
            $changes = [];
            foreach ($validated as $key => $value) {
                if ($familyPlanning->$key != $value) {
                    $changes[$key] = [
                        'from' => $familyPlanning->$key,
                        'to' => $value
                    ];
                }
            }
            
            // Only create edit record if changes were made
            if (!empty($changes)) {
                \Log::info('Changes detected', ['changes' => $changes]);
                
                // Create edit record
                FamilyPlanningEdit::create([
                    'family_planning_id' => $familyPlanning->id,
                    'user_id' => auth()->id(),
                    'changes' => $changes,
                ]);
                
                // Update the record
                $updated = $familyPlanning->update($validated);
                \Log::info('Update result', ['success' => $updated]);
                
                if ($updated) {
                    return redirect()->route('family-planning.index')
                        ->with('success', 'Family planning record updated successfully.');
                } else {
                    \Log::error('Failed to update record', ['record_id' => $familyPlanning->id]);
                    return back()->with('error', 'Failed to update the record. Please try again.');
                }
            } else {
                \Log::info('No changes detected');
                return redirect()->route('family-planning.index')
                    ->with('info', 'No changes were made to the record.');
            }
        } catch (\Exception $e) {
            \Log::error('Error updating family planning record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'An error occurred while updating the record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyPlanning  $familyPlanning
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilyPlanning $familyPlanning)
    {
        $familyPlanning->delete();

        return redirect()->route('family-planning.index')
            ->with('success', 'Family planning record deleted successfully.');
    }

    /**
     * Export Family Planning records to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function exportFamilyPlanningExcel()
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

        // Dynamically get all column names from the first record and exclude certain columns
        $exclude = ['created_at', 'updated_at', 'intended_fp_method'];
        $columns = $records->first() ? array_diff(array_keys($records->first()->getAttributes()), $exclude) : [];

        // List of date columns to format
        $dateColumns = ['birthdate', 'date_served', 'date_counselled_pregnant', 'date_encoded'];

        $callback = function() use ($records, $columns, $dateColumns) {
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
                        } catch (\Exception $e) {
                            // fallback to original value if parsing fails
                        }
                        // Prefix with tab to force Excel to treat as text
                        $value = "\t" . $value;
                    }
                    // For all numeric values, prefix with tab to force Excel to treat as text
                    elseif (is_numeric($value) && !empty($value)) {
                        $value = "\t" . $value;
                    }
                    // Ensure all values are strings
                    $row[] = (string) $value;
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
