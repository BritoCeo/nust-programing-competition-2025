<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

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
        Route::resource('medical-records', \App\Http\Controllers\MedicalRecordController::class);
        Route::get('/medical-records/{medicalRecord}/download/{index}', [\App\Http\Controllers\MedicalRecordController::class, 'downloadAttachment'])
            ->name('medical-records.download');
    });
    
    // Temporary test route for medical records without authentication
    Route::get('/medical-records-test', function () {
        return view('medical-records.index', [
            'medicalRecords' => \App\Models\MedicalRecord::with(['patient', 'doctor'])->paginate(15)
        ]);
    });
    
    // Appointments (All roles)
    Route::middleware(['permission:view appointments'])->group(function () {
        Route::resource('appointments', \App\Http\Controllers\AppointmentController::class);
        Route::post('/appointments/{appointment}/cancel', [\App\Http\Controllers\AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');
        Route::get('/appointments/today', [\App\Http\Controllers\AppointmentController::class, 'today'])
            ->name('appointments.today');
        Route::get('/appointments/upcoming', [\App\Http\Controllers\AppointmentController::class, 'upcoming'])
            ->name('appointments.upcoming');
    });
    
    // Expert System (Doctors only)
    Route::middleware(['permission:use expert system'])->group(function () {
        Route::get('/expert-system', [\App\Http\Controllers\ExpertSystemController::class, 'index'])
            ->name('expert-system.index');
        Route::post('/expert-system/analyze', [\App\Http\Controllers\ExpertSystemController::class, 'analyze'])
            ->name('expert-system.analyze');
        Route::get('/expert-system/symptoms', [\App\Http\Controllers\ExpertSystemController::class, 'getSymptomsByCategory'])
            ->name('expert-system.symptoms');
        Route::get('/expert-system/disease-info', [\App\Http\Controllers\ExpertSystemController::class, 'getDiseaseInfo'])
            ->name('expert-system.disease-info');
        Route::get('/expert-system/rules', [\App\Http\Controllers\ExpertSystemController::class, 'getRules'])
            ->name('expert-system.rules');
        Route::get('/expert-system/patient-diagnoses', [\App\Http\Controllers\ExpertSystemController::class, 'getPatientDiagnoses'])
            ->name('expert-system.patient-diagnoses');
        Route::put('/expert-system/diagnosis/{diagnosis}/status', [\App\Http\Controllers\ExpertSystemController::class, 'updateDiagnosisStatus'])
            ->name('expert-system.update-diagnosis-status');
        Route::get('/expert-system/statistics', [\App\Http\Controllers\ExpertSystemController::class, 'getStatistics'])
            ->name('expert-system.statistics');
        Route::get('/expert-system/export/{diagnosis}', [\App\Http\Controllers\ExpertSystemController::class, 'exportReport'])
            ->name('expert-system.export');
    });
    
    // Pharmacy (Pharmacists only)
    Route::middleware(['permission:view pharmacy'])->group(function () {
        Route::get('/pharmacy', function () {
            return view('pharmacy.index');
        })->name('pharmacy.index');
    });
    
    // Treatments (Doctors, Nurses)
    Route::middleware(['permission:view treatments'])->group(function () {
        Route::resource('treatments', \App\Http\Controllers\TreatmentController::class);
    });
    
    // Reports (Authorized roles)
    Route::middleware(['permission:view reports'])->group(function () {
        Route::get('/reports', function () {
            return view('reports.index');
        })->name('reports.index');
    });
});

require __DIR__.'/auth.php';
