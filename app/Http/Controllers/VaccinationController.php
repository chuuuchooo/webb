<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use App\Models\ChildProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccinationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for editing a vaccination record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vaccination = Vaccination::with('child')->findOrFail($id);
        $child = $vaccination->child;
        return view('immunization.edit_vaccination', compact('vaccination', 'child'));
    }

    /**
     * Update the vaccination record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vaccination = Vaccination::findOrFail($id);
        
        $validated = $request->validate([
            'vaccine_type' => 'required|string',
            'dose_number' => 'required|integer|min:1',
            'date_vaccinated' => 'nullable|date',
            'status' => 'required|in:Completed,Not Completed,Scheduled',
            'next_schedule' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);
        
        // Set administered by if status is changing to Completed
        if ($validated['status'] == 'Completed' && $vaccination->status != 'Completed') {
            $validated['administered_by_user_id'] = Auth::id();
        }
        
        $vaccination->update($validated);
        
        return redirect()->route('immunization.show', $vaccination->child_id)
            ->with('success', 'Vaccination record updated successfully');
    }

    /**
     * Mark a vaccination as completed.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markCompleted(Request $request, $id)
    {
        $vaccination = Vaccination::findOrFail($id);
        
        $vaccination->status = 'Completed';
        $vaccination->date_vaccinated = $request->input('date_vaccinated', now());
        $vaccination->administered_by_user_id = Auth::id();
        $vaccination->save();
        
        return redirect()->back()->with('success', 'Vaccination marked as completed');
    }
} 