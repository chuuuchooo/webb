<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the user's account details
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('account.index', compact('user'));
    }
    
    /**
     * Show the form for editing the user's account details
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit()
    {
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }
    
    /**
     * Update the user's account details
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'required|string|in:Male,Female',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Handle password update
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('account.index')
            ->with('success', 'Your account has been updated successfully.');
    }
}
