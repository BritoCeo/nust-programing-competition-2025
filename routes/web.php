<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\MedicalRecordController;
use App\Http\Controllers\Web\AppointmentController;
use App\Http\Controllers\Web\ExpertSystemController;
use App\Http\Controllers\Web\PharmacyController;
use App\Http\Controllers\Web\ReportController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Test route to check if everything is working
Route::get('/test', function () {
    return 'Test route is working!';
});

// Test medical records route without authentication
Route::get('/test-medical-records', function () {
    try {
        $records = \App\Models\MedicalRecord::with(['patient', 'doctor'])->take(5)->get();
        return response()->json([
            'success' => true,
            'message' => 'Medical records loaded successfully',
            'data' => $records
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error loading medical records: ' . $e->getMessage()
        ]);
    }
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard with role-based access
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authentication routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });
    
    // Medical Records (Doctors, Nurses, Patients)
    Route::middleware(['permission:view medical records'])->group(function () {
        Route::resource('medical-records', MedicalRecordController::class);
        Route::get('/medical-records/{medicalRecord}/download/{index}', [MedicalRecordController::class, 'downloadAttachment'])
            ->name('medical-records.download');
        Route::post('/medical-records/{medicalRecord}/archive', [MedicalRecordController::class, 'archive'])
            ->name('medical-records.archive');
        Route::post('/medical-records/{medicalRecord}/restore', [MedicalRecordController::class, 'restore'])
            ->name('medical-records.restore');
    });
    
    // Temporary test route for medical records without authentication
    Route::get('/medical-records-test', function () {
        return view('medical-records.index', [
            'medicalRecords' => \App\Models\MedicalRecord::with(['patient', 'doctor'])->paginate(15)
        ]);
    });
    
    // Debug route for medical record show
    Route::get('/medical-records-debug/{id}', function ($id) {
        $medicalRecord = \App\Models\MedicalRecord::with(['patient', 'doctor'])->find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical record not found'], 404);
        }
        return response()->json([
            'id' => $medicalRecord->id,
            'record_number' => $medicalRecord->record_number,
            'patient' => $medicalRecord->patient->name,
            'doctor' => $medicalRecord->doctor->name
        ]);
    });
    
    // Appointments (All roles)
    Route::middleware(['permission:view appointments'])->group(function () {
        Route::resource('appointments', AppointmentController::class);
        Route::post('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])
            ->name('appointments.confirm');
        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');
        Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])
            ->name('appointments.complete');
        Route::get('/appointments/today', [AppointmentController::class, 'today'])
            ->name('appointments.today');
        Route::get('/appointments/upcoming', [AppointmentController::class, 'upcoming'])
            ->name('appointments.upcoming');
        Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])
            ->name('appointments.calendar');
    });
    
    // Expert System (Doctors only)
    Route::middleware(['permission:use expert system'])->group(function () {
        Route::get('/expert-system', [ExpertSystemController::class, 'index'])
            ->name('expert-system.index');
        Route::post('/expert-system/analyze', [ExpertSystemController::class, 'analyze'])
            ->name('expert-system.analyze');
        Route::get('/expert-system/symptoms', [ExpertSystemController::class, 'getSymptomsByCategory'])
            ->name('expert-system.symptoms');
        Route::get('/expert-system/disease-info', [ExpertSystemController::class, 'getDiseaseInfo'])
            ->name('expert-system.disease-info');
        Route::get('/expert-system/rules', [ExpertSystemController::class, 'getRules'])
            ->name('expert-system.rules');
        Route::get('/expert-system/patient-diagnoses/{patient}', [ExpertSystemController::class, 'getPatientDiagnoses'])
            ->name('expert-system.patient-diagnoses');
        Route::put('/expert-system/diagnosis/{diagnosis}/status', [ExpertSystemController::class, 'updateDiagnosisStatus'])
            ->name('expert-system.update-diagnosis-status');
        Route::get('/expert-system/statistics', [ExpertSystemController::class, 'getStatistics'])
            ->name('expert-system.statistics');
        Route::get('/expert-system/export/{diagnosis}', [ExpertSystemController::class, 'exportReport'])
            ->name('expert-system.export');
    });
    
    // Pharmacy (Pharmacists only)
    Route::middleware(['permission:view pharmacy'])->group(function () {
        Route::get('/pharmacy', [PharmacyController::class, 'index'])->name('pharmacy.index');
        Route::get('/pharmacy/drugs', [PharmacyController::class, 'getDrugs'])->name('pharmacy.drugs');
        Route::get('/pharmacy/inventory', [PharmacyController::class, 'getInventory'])->name('pharmacy.inventory');
        Route::post('/pharmacy/dispense', [PharmacyController::class, 'dispenseDrug'])->name('pharmacy.dispense');
        Route::get('/pharmacy/prescriptions', [PharmacyController::class, 'getPrescriptions'])->name('pharmacy.prescriptions');
        Route::post('/pharmacy/prescriptions/{prescription}/fulfill', [PharmacyController::class, 'fulfillPrescription'])->name('pharmacy.fulfill');
        Route::get('/pharmacy/categories', [PharmacyController::class, 'getCategories'])->name('pharmacy.categories');
        Route::get('/pharmacy/statistics', [PharmacyController::class, 'getStatistics'])->name('pharmacy.statistics');
    });
    
    // Treatments (Doctors, Nurses)
    Route::middleware(['permission:view treatments'])->group(function () {
        Route::resource('treatments', \App\Http\Controllers\TreatmentController::class);
    });
    
    // Reports (Authorized roles)
    Route::middleware(['permission:view reports'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
        Route::get('/reports/templates', [ReportController::class, 'getTemplates'])->name('reports.templates');
        Route::post('/reports/schedule', [ReportController::class, 'scheduleReport'])->name('reports.schedule');
    });
    
    // Notifications (All authenticated users)
    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Web\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{notification}/read', [\App\Http\Controllers\Web\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [\App\Http\Controllers\Web\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/{notification}', [\App\Http\Controllers\Web\NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/unread-count', [\App\Http\Controllers\Web\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    });
});
