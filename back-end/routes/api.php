<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Client routes
    Route::apiResource('clients', ClientController::class);
    
    // Pet routes
    Route::apiResource('pets', PetController::class);
    
    // Appointment routes
    Route::apiResource('appointments', AppointmentController::class);
    
    // Medical record routes
    Route::apiResource('medical-records', MedicalRecordController::class);
    
    // Payment routes
    Route::apiResource('payments', PaymentController::class);
    
    // Report routes
    Route::prefix('reports')->group(function () {
        Route::get('/appointments-by-period', [ReportController::class, 'appointmentsByPeriod']);
        Route::get('/appointments-by-veterinarian', [ReportController::class, 'appointmentsByVeterinarian']);
        Route::get('/pets-by-client', [ReportController::class, 'petsByClient']);
        Route::get('/dashboard-stats', [ReportController::class, 'dashboardStats']);
    });
    
    // Veterinarian routes
    Route::get('/veterinarians', function () {
        return response()->json(\App\Models\User::where('role', 'veterinarian')->get(['id', 'name', 'crmv']));
    });
});
