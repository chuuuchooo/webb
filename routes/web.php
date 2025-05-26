<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FamilyPlanningController;
use App\Http\Controllers\ChildProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\ImmunizationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // User Export Reports
    Route::get('/user/export-reports', function() { return view('user.export-reports'); })->name('user.export-reports');
    Route::get('/user/export-family-planning-csv', [App\Http\Controllers\HomeController::class, 'exportFamilyPlanningCsv'])->name('user.exportFamilyPlanningCsv');
    Route::get('/user/export-immunization-csv', [App\Http\Controllers\HomeController::class, 'exportImmunizationCsv'])->name('user.exportImmunizationCsv');
    // User Family Planning Dashboard Stats API (session-authenticated)
    Route::get('/api/user/family-planning-stats', [\App\Http\Controllers\Api\ChartDataController::class, 'getUserFamilyPlanningStats']);
    // Main /home route redirects based on user role
Route::get('/home', function() {
    $user = auth()->user();
    if ($user && $user->isAdmin) {
        return redirect('/admin/home');
    } else {
        return redirect('/user/home');
    }
})->name('home');

// Admin dashboard
Route::get('/admin/home', [HomeController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.home');

// User dashboard
Route::get('/user/home', [HomeController::class, 'index'])->middleware(['auth'])->name('user.home');
    
    Route::get('/analytics', function () {
        $familyPlanningCount = \App\Models\FamilyPlanning::count();
        $immunizationCount = \App\Models\ImmunizationRecord::count();
        $totalRecords = $familyPlanningCount + $immunizationCount;
        return view('analytics', compact('totalRecords', 'familyPlanningCount', 'immunizationCount'));
    })->name('analytics');
    
    Route::resource('family-planning', FamilyPlanningController::class);
    Route::resource('immunization', ImmunizationController::class);
    Route::get('/immunization-dashboard', [ImmunizationController::class, 'dashboard'])->name('immunization.dashboard');
    
    // Vaccination routes
    Route::get('/vaccination/{id}/edit', [VaccinationController::class, 'edit'])->name('vaccination.edit');
    Route::put('/vaccination/{id}', [VaccinationController::class, 'update'])->name('vaccination.update');
    Route::post('/vaccination/{id}/complete', [VaccinationController::class, 'markCompleted'])->name('vaccination.complete');
    
    // Change profile routes to account routes for regular users
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account/update', [AccountController::class, 'update'])->name('account.update');

    Route::get('/bmi-calculator', [HomeController::class, 'bmiCalculator'])->name('bmi.calculator');
});

// Admin routes

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export-family-planning', [FamilyPlanningController::class, 'exportFamilyPlanningExcel'])->name('exportFamilyPlanningExcel');
    Route::get('/reports/export-immunization', [ChildProfileController::class, 'exportImmunizationExcel'])->name('exportImmunizationExcel');
    Route::get('/user-activity', [AdminController::class, 'userActivity'])->name('user-activity');
    Route::get('/export-reports', [AdminController::class, 'exportReports'])->name('export-reports');
    Route::get('/family-planning-records', [AdminController::class, 'familyPlanningRecords'])->name('family-planning-records');
    Route::get('/family-planning/{id}/edit-history', [AdminController::class, 'familyPlanningEditHistory'])->name('family-planning.edit-history');
    
    // Admin still has access to manage user profiles
    Route::resource('profile', ProfileController::class);
});
