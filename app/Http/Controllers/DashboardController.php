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
                'total_appointments' => Appointment::count(),
                'total_medical_records' => MedicalRecord::count(),
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
}
