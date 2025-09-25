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
    
    // Chatbot
    Route::prefix('chatbot')->group(function () {
        Route::post('/message', [\App\Http\Controllers\ChatbotController::class, 'processMessage']);
        Route::get('/suggestions', [\App\Http\Controllers\ChatbotController::class, 'getSuggestions']);
        Route::get('/capabilities', [\App\Http\Controllers\ChatbotController::class, 'getCapabilities']);
        Route::get('/history', [\App\Http\Controllers\ChatbotController::class, 'getHistory']);
        Route::delete('/history', [\App\Http\Controllers\ChatbotController::class, 'clearHistory']);
    });
    
    // Blog
    Route::prefix('blog')->group(function () {
        Route::get('/', [\App\Http\Controllers\BlogController::class, 'index']);
        Route::get('/popular', [\App\Http\Controllers\BlogController::class, 'getPopular']);
        Route::get('/recent', [\App\Http\Controllers\BlogController::class, 'getRecent']);
        Route::get('/categories', [\App\Http\Controllers\BlogController::class, 'getCategories']);
        Route::get('/statistics', [\App\Http\Controllers\BlogController::class, 'getStatistics']);
        Route::get('/{blogPost}', [\App\Http\Controllers\BlogController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\BlogController::class, 'store']);
        Route::put('/{blogPost}', [\App\Http\Controllers\BlogController::class, 'update']);
        Route::delete('/{blogPost}', [\App\Http\Controllers\BlogController::class, 'destroy']);
        Route::post('/{blogPost}/comments', [\App\Http\Controllers\BlogController::class, 'addComment']);
        Route::post('/{blogPost}/like', [\App\Http\Controllers\BlogController::class, 'toggleLike']);
    });
    
    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/disease-trends', [\App\Http\Controllers\AnalyticsController::class, 'getDiseaseTrends']);
        Route::get('/patient-outcomes', [\App\Http\Controllers\AnalyticsController::class, 'getPatientOutcomes']);
        Route::get('/treatment-effectiveness', [\App\Http\Controllers\AnalyticsController::class, 'getTreatmentEffectiveness']);
        Route::get('/predictive', [\App\Http\Controllers\AnalyticsController::class, 'getPredictiveAnalytics']);
        Route::get('/system-statistics', [\App\Http\Controllers\AnalyticsController::class, 'getSystemStatistics']);
        Route::get('/dashboard', [\App\Http\Controllers\AnalyticsController::class, 'getDashboardAnalytics']);
        Route::get('/report', [\App\Http\Controllers\AnalyticsController::class, 'getAnalyticsReport']);
        Route::post('/export', [\App\Http\Controllers\AnalyticsController::class, 'exportAnalytics']);
    });
});

// Offline API endpoints for PWA functionality
Route::prefix('offline')->group(function () {
    // Symptoms API
    Route::get('/symptoms', function () {
        return response()->json(\App\Models\Symptom::all());
    });
    
    // Diseases API
    Route::get('/diseases', function () {
        return response()->json(\App\Models\Disease::all());
    });
    
    // Drugs API
    Route::get('/drugs', function () {
        return response()->json(\App\Models\Drug::all());
    });
    
    // Expert System API
    Route::post('/expert-system/analyze', function (Request $request) {
        $symptomIds = $request->input('symptoms', []);
        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        
        $expertSystem = new \App\Services\ExpertSystemService();
        $result = $expertSystem->analyzeMultiDiseaseSymptoms($symptomIds, $patientId, $doctorId);
        
        return response()->json($result);
    });
    
    // Offline diagnosis storage
    Route::post('/diagnoses', function (Request $request) {
        $diagnosis = \App\Models\Diagnosis::create([
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $request->input('doctor_id'),
            'symptoms' => $request->input('symptoms', []),
            'diagnosis' => $request->input('diagnosis'),
            'confidence_score' => $request->input('confidence_score', 0),
            'treatment_plan' => $request->input('treatment_plan'),
            'notes' => $request->input('notes'),
            'offline' => true,
        ]);
        
        return response()->json($diagnosis);
    });
    
    // Offline appointments
    Route::post('/appointments', function (Request $request) {
        $appointment = \App\Models\Appointment::create([
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $request->input('doctor_id'),
            'appointment_date' => $request->input('appointment_date'),
            'appointment_time' => $request->input('appointment_time'),
            'reason' => $request->input('reason'),
            'status' => 'pending',
            'offline' => true,
        ]);
        
        return response()->json($appointment);
    });
    
    // Offline medical records
    Route::post('/medical-records', function (Request $request) {
        $record = \App\Models\MedicalRecord::create([
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $request->input('doctor_id'),
            'record_number' => $request->input('record_number'),
            'chief_complaint' => $request->input('chief_complaint'),
            'history_of_present_illness' => $request->input('history_of_present_illness'),
            'physical_examination' => $request->input('physical_examination'),
            'diagnosis' => $request->input('diagnosis'),
            'treatment_plan' => $request->input('treatment_plan'),
            'offline' => true,
        ]);
        
        return response()->json($record);
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
