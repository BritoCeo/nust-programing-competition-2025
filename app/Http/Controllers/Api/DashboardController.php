<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Treatment;
use App\Models\DrugAdministration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        $user = Auth::user();
        $stats = [];

        switch ($user->role_name) {
            case 'admin':
                $stats = $this->getAdminStats();
                break;
            case 'doctor':
                $stats = $this->getDoctorStats($user);
                break;
            case 'nurse':
                $stats = $this->getNurseStats($user);
                break;
            case 'pharmacist':
                $stats = $this->getPharmacistStats($user);
                break;
            case 'receptionist':
                $stats = $this->getReceptionistStats($user);
                break;
            case 'patient':
                $stats = $this->getPatientStats($user);
                break;
            default:
                $stats = $this->getDefaultStats();
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get recent activity
     */
    public function recentActivity(Request $request)
    {
        $user = Auth::user();
        $activities = [];

        // Get recent medical records
        $recentRecords = MedicalRecord::with(['patient', 'doctor'])
            ->when($user->hasRole('patient'), function ($query) use ($user) {
                return $query->where('patient_id', $user->id);
            })
            ->when($user->hasRole('doctor'), function ($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($record) {
                return [
                    'type' => 'medical_record',
                    'title' => 'Medical Record Created',
                    'description' => "Record #{$record->record_number} for {$record->patient->name}",
                    'timestamp' => $record->created_at,
                    'icon' => 'fas fa-file-medical',
                    'color' => 'blue'
                ];
            });

        // Get recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->when($user->hasRole('patient'), function ($query) use ($user) {
                return $query->where('patient_id', $user->id);
            })
            ->when($user->hasRole('doctor'), function ($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'type' => 'appointment',
                    'title' => 'Appointment Scheduled',
                    'description' => "Appointment with {$appointment->patient->name} on {$appointment->appointment_date->format('M d, Y')}",
                    'timestamp' => $appointment->created_at,
                    'icon' => 'fas fa-calendar-check',
                    'color' => 'green'
                ];
            });

        // Get recent diagnoses
        $recentDiagnoses = Diagnosis::with(['patient', 'doctor', 'disease'])
            ->when($user->hasRole('patient'), function ($query) use ($user) {
                return $query->where('patient_id', $user->id);
            })
            ->when($user->hasRole('doctor'), function ($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($diagnosis) {
                return [
                    'type' => 'diagnosis',
                    'title' => 'Diagnosis Made',
                    'description' => "Diagnosed {$diagnosis->disease->name} for {$diagnosis->patient->name}",
                    'timestamp' => $diagnosis->created_at,
                    'icon' => 'fas fa-stethoscope',
                    'color' => 'purple'
                ];
            });

        // Combine and sort activities
        $activities = collect()
            ->merge($recentRecords)
            ->merge($recentAppointments)
            ->merge($recentDiagnoses)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Get admin statistics
     */
    private function getAdminStats()
    {
        return [
            'total_users' => User::count(),
            'total_patients' => User::role('patient')->count(),
            'total_doctors' => User::role('doctor')->count(),
            'total_nurses' => User::role('nurse')->count(),
            'total_pharmacists' => User::role('pharmacist')->count(),
            'total_medical_records' => MedicalRecord::count(),
            'total_appointments' => Appointment::count(),
            'total_diagnoses' => Diagnosis::count(),
            'active_treatments' => Treatment::where('status', 'active')->count(),
            'pending_prescriptions' => DrugAdministration::where('status', 'prescribed')->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'this_month_records' => MedicalRecord::whereMonth('created_at', now()->month)->count()
        ];
    }

    /**
     * Get doctor statistics
     */
    private function getDoctorStats($user)
    {
        return [
            'my_patients' => $user->patients()->count(),
            'today_appointments' => Appointment::where('doctor_id', $user->id)
                ->whereDate('appointment_date', today())->count(),
            'pending_diagnoses' => Diagnosis::where('doctor_id', $user->id)
                ->where('status', 'tentative')->count(),
            'active_treatments' => Treatment::where('doctor_id', $user->id)
                ->where('status', 'active')->count(),
            'this_month_records' => MedicalRecord::where('doctor_id', $user->id)
                ->whereMonth('created_at', now()->month)->count(),
            'upcoming_appointments' => Appointment::where('doctor_id', $user->id)
                ->where('appointment_date', '>', now())
                ->where('status', 'scheduled')->count()
        ];
    }

    /**
     * Get nurse statistics
     */
    private function getNurseStats($user)
    {
        return [
            'assigned_patients' => 0, // Will implement later
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_treatments' => Treatment::where('status', 'active')->count(),
            'completed_treatments' => Treatment::where('status', 'completed')->count(),
            'this_month_treatments' => Treatment::whereMonth('created_at', now()->month)->count()
        ];
    }

    /**
     * Get pharmacist statistics
     */
    private function getPharmacistStats($user)
    {
        return [
            'pending_prescriptions' => DrugAdministration::where('status', 'prescribed')->count(),
            'dispensed_today' => DrugAdministration::where('status', 'dispensed')
                ->whereDate('dispense_date', today())->count(),
            'low_stock_items' => 0, // Will implement later
            'total_drugs' => DrugAdministration::count(),
            'this_month_dispensed' => DrugAdministration::where('status', 'dispensed')
                ->whereMonth('dispense_date', now()->month)->count()
        ];
    }

    /**
     * Get receptionist statistics
     */
    private function getReceptionistStats($user)
    {
        return [
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
            'new_patients' => User::role('patient')->whereDate('created_at', today())->count(),
            'total_appointments' => Appointment::count(),
            'this_month_appointments' => Appointment::whereMonth('created_at', now()->month)->count()
        ];
    }

    /**
     * Get patient statistics
     */
    private function getPatientStats($user)
    {
        return [
            'my_appointments' => $user->appointments()->count(),
            'upcoming_appointments' => $user->appointments()
                ->where('appointment_date', '>=', today())
                ->where('status', 'scheduled')->count(),
            'medical_records' => $user->medicalRecords()->count(),
            'active_treatments' => Treatment::where('patient_id', $user->id)
                ->where('status', 'active')->count(),
            'total_diagnoses' => Diagnosis::where('patient_id', $user->id)->count()
        ];
    }

    /**
     * Get default statistics
     */
    private function getDefaultStats()
    {
        return [
            'total_users' => User::count(),
            'total_medical_records' => MedicalRecord::count(),
            'total_appointments' => Appointment::count(),
            'total_diagnoses' => Diagnosis::count()
        ];
    }
}
