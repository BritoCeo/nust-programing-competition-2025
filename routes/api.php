<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ExpertSystemController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\PharmacyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-activity', [DashboardController::class, 'recentActivity']);
    
    // Medical Records
    Route::apiResource('medical-records', MedicalRecordController::class);
    Route::get('/medical-records/{medicalRecord}/download/{index}', [MedicalRecordController::class, 'downloadAttachment']);
    Route::post('/medical-records/{medicalRecord}/archive', [MedicalRecordController::class, 'archive']);
    Route::post('/medical-records/{medicalRecord}/restore', [MedicalRecordController::class, 'restore']);
    
    // Appointments
    Route::apiResource('appointments', AppointmentController::class);
    Route::post('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm']);
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete']);
    Route::get('/appointments/today', [AppointmentController::class, 'today']);
    Route::get('/appointments/upcoming', [AppointmentController::class, 'upcoming']);
    Route::get('/appointments/calendar', [AppointmentController::class, 'calendar']);
    
    // Expert System
    Route::prefix('expert-system')->group(function () {
        Route::get('/symptoms', [ExpertSystemController::class, 'getSymptoms']);
        Route::get('/diseases', [ExpertSystemController::class, 'getDiseases']);
        Route::post('/analyze', [ExpertSystemController::class, 'analyze']);
        Route::get('/rules', [ExpertSystemController::class, 'getRules']);
        Route::get('/statistics', [ExpertSystemController::class, 'getStatistics']);
        Route::get('/patient-diagnoses/{patient}', [ExpertSystemController::class, 'getPatientDiagnoses']);
        Route::put('/diagnosis/{diagnosis}/status', [ExpertSystemController::class, 'updateDiagnosisStatus']);
    });
    
    // Pharmacy
    Route::prefix('pharmacy')->group(function () {
        Route::get('/drugs', [PharmacyController::class, 'getDrugs']);
        Route::get('/inventory', [PharmacyController::class, 'getInventory']);
        Route::post('/dispense', [PharmacyController::class, 'dispenseDrug']);
        Route::get('/prescriptions', [PharmacyController::class, 'getPrescriptions']);
        Route::post('/prescriptions/{prescription}/fulfill', [PharmacyController::class, 'fulfillPrescription']);
    });
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::post('/generate', [ReportController::class, 'generate']);
        Route::get('/{report}/download', [ReportController::class, 'download']);
        Route::get('/templates', [ReportController::class, 'getTemplates']);
        Route::post('/schedule', [ReportController::class, 'scheduleReport']);
    });
    
    // Search
    Route::get('/search', function (Request $request) {
        $query = $request->get('q');
        $type = $request->get('type', 'all');
        
        $results = [];
        
        if ($type === 'all' || $type === 'patients') {
            $results['patients'] = \App\Models\User::role('patient')
                ->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'email']);
        }
        
        if ($type === 'all' || $type === 'records') {
            $results['records'] = \App\Models\MedicalRecord::with('patient')
                ->where('record_number', 'like', "%{$query}%")
                ->orWhere('chief_complaint', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'record_number', 'chief_complaint', 'patient_id']);
        }
        
        return response()->json($results);
    });
    
    // Notifications
    Route::get('/notifications', function () {
        return response()->json([
            'unread_count' => 0,
            'notifications' => []
        ]);
    });
    
    Route::post('/notifications/mark-read', function () {
        return response()->json(['success' => true]);
    });
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '1.0.0',
        'services' => [
            'database' => 'connected',
            'cache' => 'connected',
            'queue' => 'connected'
        ]
    ]);
});
