<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ImmunizationController extends Controller
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
        // Redirect to ChildProfileController index
        return app(ChildProfileController::class)->index($request);
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
        // Redirect to ChildProfileController store
        return app(ChildProfileController::class)->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Find the child profile
            $child = ChildProfile::findOrFail($id);
            return app(ChildProfileController::class)->show($child);
        } catch (\Exception $e) {
            return abort(404, 'Child profile not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            // Find the child profile
            $child = ChildProfile::findOrFail($id);
            return app(ChildProfileController::class)->edit($child);
        } catch (\Exception $e) {
            return abort(404, 'Child profile not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the child profile
            $child = ChildProfile::findOrFail($id);
            return app(ChildProfileController::class)->update($request, $child);
        } catch (\Exception $e) {
            return abort(404, 'Child profile not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the child profile
            $child = ChildProfile::findOrFail($id);
            return app(ChildProfileController::class)->destroy($child);
        } catch (\Exception $e) {
            return abort(404, 'Child profile not found');
        }
    }
    
    /**
     * Dashboard view
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return app(ChildProfileController::class)->dashboard();
    }
}
