<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\DrugAdministration;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        switch ($user->role_name) {
            case 'admin':
                $data = $this->getAdminDashboard();
                break;
            case 'doctor':
                $data = $this->getDoctorDashboard();
                break;
            case 'nurse':
                $data = $this->getNurseDashboard();
                break;
            case 'pharmacist':
                $data = $this->getPharmacistDashboard();
                break;
            case 'receptionist':
                $data = $this->getReceptionistDashboard();
                break;
            case 'patient':
                $data = $this->getPatientDashboard();
                break;
            default:
                $data = $this->getDefaultDashboard();
        }

        // Add recent activity data
        $data['recent_activity'] = $this->getRecentActivity($user);
        
        // Add upcoming appointments for relevant roles
        if (in_array($user->role_name, ['doctor', 'nurse', 'patient'])) {
            $data['upcoming_appointments'] = $this->getUpcomingAppointments($user);
        }

        return view('dashboard', compact('data'));
    }

    private function getAdminDashboard()
    {
        return [
            'title' => 'Admin Dashboard',
            'stats' => [
                'total_users' => User::count(),
                'total_patients' => User::role('patient')->count(),
                'total_doctors' => User::role('doctor')->count(),
                'total_nurses' => User::role('nurse')->count(),
                'total_pharmacists' => User::role('pharmacist')->count(),
                'total_appointments' => Appointment::count(),
                'total_medical_records' => MedicalRecord::count(),
                'total_diagnoses' => \App\Models\Diagnosis::count(),
                'active_treatments' => Treatment::where('status', 'active')->count(),
                'pending_prescriptions' => DrugAdministration::where('status', 'prescribed')->count(),
                'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
                'this_month_records' => MedicalRecord::whereMonth('created_at', now()->month)->count()
            ],
            'modules' => [
                'user_management' => true,
                'medical_records' => true,
                'appointments' => true,
                'diagnoses' => true,
                'treatments' => true,
                'pharmacy' => true,
                'reports' => true,
                'expert_system' => true,
            ]
        ];
    }

    private function getDoctorDashboard()
    {
        $doctor = Auth::user();

        return [
            'title' => 'Doctor Dashboard',
            'stats' => [
                'my_patients' => $doctor->patients()->count(),
                'today_appointments' => Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', today())->count(),
                'pending_diagnoses' => \App\Models\Diagnosis::where('doctor_id', $doctor->id)
                    ->where('status', 'tentative')->count(),
                'active_treatments' => Treatment::where('doctor_id', $doctor->id)
                    ->where('status', 'active')->count(),
                'total_medical_records' => \App\Models\MedicalRecord::where('doctor_id', $doctor->id)->count(),
                'upcoming_appointments' => Appointment::where('doctor_id', $doctor->id)
                    ->where('appointment_date', '>', today())
                    ->where('status', 'scheduled')->count(),
                'this_month_records' => \App\Models\MedicalRecord::where('doctor_id', $doctor->id)
                    ->whereMonth('created_at', now()->month)->count(),
                'total_diagnoses' => \App\Models\Diagnosis::where('doctor_id', $doctor->id)->count()
            ],
            'modules' => [
                'medical_records' => true,
                'appointments' => true,
                'diagnoses' => true,
                'treatments' => true,
                'reports' => true,
                'expert_system' => true,
            ]
        ];
    }

    private function getNurseDashboard()
    {
        $nurse = Auth::user();
        
        return [
            'title' => 'Nurse Dashboard',
            'stats' => [
                'assigned_patients' => 0, // Will implement later
                'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
                'pending_treatments' => Treatment::where('status', 'active')->count(),
                'completed_treatments' => Treatment::where('status', 'completed')->count(),
                'this_month_treatments' => Treatment::whereMonth('created_at', now()->month)->count(),
                'total_medical_records' => MedicalRecord::count(),
                'active_treatments' => Treatment::where('status', 'active')->count()
            ],
            'modules' => [
                'medical_records' => true,
                'appointments' => true,
                'treatments' => true,
                'reports' => true,
            ]
        ];
    }

    private function getPharmacistDashboard()
    {
        return [
            'title' => 'Pharmacist Dashboard',
            'stats' => [
                'pending_prescriptions' => DrugAdministration::where('status', 'prescribed')->count(),
                'dispensed_today' => DrugAdministration::where('status', 'dispensed')
                    ->whereDate('dispense_date', today())->count(),
                'low_stock_items' => 0, // Will implement later
                'total_drugs' => DrugAdministration::count(),
                'this_month_dispensed' => DrugAdministration::where('status', 'dispensed')
                    ->whereMonth('dispense_date', now()->month)->count(),
                'active_treatments' => DrugAdministration::where('status', 'active')->count()
            ],
            'modules' => [
                'pharmacy' => true,
                'treatments' => true,
                'reports' => true,
            ]
        ];
    }

    private function getReceptionistDashboard()
    {
        return [
            'title' => 'Receptionist Dashboard',
            'stats' => [
                'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
                'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
                'new_patients' => User::role('patient')->whereDate('created_at', today())->count(),
                'total_appointments' => Appointment::count(),
                'this_month_appointments' => Appointment::whereMonth('created_at', now()->month)->count(),
                'total_patients' => User::role('patient')->count()
            ],
            'modules' => [
                'appointments' => true,
                'users' => true,
                'reports' => true,
            ]
        ];
    }

    private function getPatientDashboard()
    {
        $patient = Auth::user();

        return [
            'title' => 'Patient Dashboard',
            'stats' => [
                'my_appointments' => $patient->appointments()->count(),
                'upcoming_appointments' => $patient->appointments()
                    ->where('appointment_date', '>=', today())
                    ->where('status', 'scheduled')->count(),
                'medical_records' => $patient->medicalRecords()->count(),
                'active_treatments' => Treatment::where('patient_id', $patient->id)
                    ->where('status', 'active')->count(),
                'total_diagnoses' => \App\Models\Diagnosis::where('patient_id', $patient->id)->count(),
                'today_appointments' => $patient->appointments()
                    ->whereDate('appointment_date', today())->count(),
            ],
            'modules' => [
                'medical_records' => true,
                'appointments' => true,
                'treatments' => true,
            ]
        ];
    }

    private function getDefaultDashboard()
    {
        return [
            'title' => 'Dashboard',
            'stats' => [],
            'modules' => []
        ];
    }

    /**
     * Get recent activity for the user
     */
    private function getRecentActivity($user)
    {
        $activities = collect();

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
        $recentDiagnoses = \App\Models\Diagnosis::with(['patient', 'doctor', 'disease'])
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
        return $activities
            ->merge($recentRecords)
            ->merge($recentAppointments)
            ->merge($recentDiagnoses)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();
    }

    /**
     * Get upcoming appointments for the user
     */
    private function getUpcomingAppointments($user)
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->where('appointment_date', '>=', today())
            ->where('status', '!=', 'cancelled');

        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        return $query->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();
    }
}
