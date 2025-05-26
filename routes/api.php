<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\ChartDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard Analytics API Routes
Route::middleware('auth')->group(function () {
    // Family Planning Analytics
    Route::get('/family-planning/analytics', [HomeController::class, 'getFamilyPlanningAnalytics']);
    // New: User Family Planning Dashboard Stats
    Route::get('/user/family-planning-stats', [\App\Http\Controllers\Api\ChartDataController::class, 'getUserFamilyPlanningStats']);
    
    // Immunization Analytics
    Route::get('/immunization/analytics', [HomeController::class, 'getImmunizationAnalytics']);
});

// Admin Dashboard Analytics API Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Employee Analytics
    Route::get('/employee/analytics', [AdminController::class, 'getEmployeeAnalytics']);
    
    // Family Planning Analytics
    Route::get('/family-planning/analytics', [AdminController::class, 'getAdminFamilyPlanningAnalytics']);
    
    // Immunization Analytics
    Route::get('/immunization/analytics', [AdminController::class, 'getAdminImmunizationAnalytics']);
    
    // Monthly Reports Data
    Route::get('/monthly-reports', [AdminController::class, 'getMonthlyReportsData']);
    
    // User Reports Analytics
    Route::get('/user-reports/analytics', [AdminController::class, 'getUserReportsAnalytics']);
    
    // User Analytics
    Route::get('/user-analytics', [AdminController::class, 'getUserAnalytics']);
});

// Chart Data Routes
Route::get('/family-planning/stats', [ChartDataController::class, 'getFamilyPlanningStats']);
Route::get('/immunization/stats', [ChartDataController::class, 'getImmunizationStats']);
