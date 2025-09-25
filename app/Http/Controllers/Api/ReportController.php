<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Get available reports
     */
    public function index(Request $request)
    {
        $reports = Report::where('generated_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Generate a new report
     */
    public function generate(Request $request)
    {
        $validator = $request->validate([
            'report_type' => 'required|string|in:patient_summary,diagnosis_report,treatment_report,appointment_report,pharmacy_report,expert_system_report,custom',
            'report_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'filters' => 'nullable|array',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start'
        ]);

        $report = Report::create([
            'report_name' => $request->report_name,
            'report_type' => $request->report_type,
            'description' => $request->description,
            'filters' => $request->filters ?? [],
            'report_date' => now(),
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'generated_by' => Auth::id(),
            'status' => 'generating'
        ]);

        // Generate report data based on type
        $reportData = $this->generateReportData($report);
        
        $report->update([
            'report_data' => $reportData,
            'status' => 'completed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'data' => $report
        ]);
    }

    /**
     * Download report
     */
    public function download(Report $report)
    {
        if ($report->generated_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to report'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Get report templates
     */
    public function getTemplates()
    {
        $templates = [
            [
                'id' => 'patient_summary',
                'name' => 'Patient Summary Report',
                'description' => 'Comprehensive patient medical history and current status',
                'icon' => 'fas fa-user-md',
                'color' => 'blue'
            ],
            [
                'id' => 'diagnosis_report',
                'name' => 'Diagnosis Report',
                'description' => 'Analysis of diagnoses made by the expert system',
                'icon' => 'fas fa-stethoscope',
                'color' => 'purple'
            ],
            [
                'id' => 'treatment_report',
                'name' => 'Treatment Report',
                'description' => 'Overview of treatments and their effectiveness',
                'icon' => 'fas fa-pills',
                'color' => 'green'
            ],
            [
                'id' => 'appointment_report',
                'name' => 'Appointment Report',
                'description' => 'Scheduling and attendance statistics',
                'icon' => 'fas fa-calendar-check',
                'color' => 'orange'
            ],
            [
                'id' => 'expert_system_report',
                'name' => 'Expert System Report',
                'description' => 'AI system performance and accuracy metrics',
                'icon' => 'fas fa-brain',
                'color' => 'indigo'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Schedule recurring report
     */
    public function scheduleReport(Request $request)
    {
        $validator = $request->validate([
            'report_type' => 'required|string',
            'report_name' => 'required|string|max:255',
            'schedule_frequency' => 'required|string|in:daily,weekly,monthly',
            'email_recipients' => 'nullable|array',
            'email_recipients.*' => 'email'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report scheduled successfully'
        ]);
    }

    /**
     * Generate report data based on type
     */
    private function generateReportData(Report $report)
    {
        $filters = $report->filters ?? [];
        $startDate = $report->period_start ? Carbon::parse($report->period_start) : now()->subMonth();
        $endDate = $report->period_end ? Carbon::parse($report->period_end) : now();

        switch ($report->report_type) {
            case 'patient_summary':
                return $this->generatePatientSummaryReport($filters, $startDate, $endDate);
            
            case 'diagnosis_report':
                return $this->generateDiagnosisReport($filters, $startDate, $endDate);
            
            case 'treatment_report':
                return $this->generateTreatmentReport($filters, $startDate, $endDate);
            
            case 'appointment_report':
                return $this->generateAppointmentReport($filters, $startDate, $endDate);
            
            case 'expert_system_report':
                return $this->generateExpertSystemReport($filters, $startDate, $endDate);
            
            default:
                return $this->generateCustomReport($filters, $startDate, $endDate);
        }
    }

    /**
     * Generate patient summary report
     */
    private function generatePatientSummaryReport($filters, $startDate, $endDate)
    {
        $patients = User::role('patient')
            ->when(isset($filters['patient_id']), function ($query) use ($filters) {
                return $query->where('id', $filters['patient_id']);
            })
            ->with(['medicalRecords', 'appointments'])
            ->get();

        return [
            'total_patients' => $patients->count(),
            'new_patients' => $patients->where('created_at', '>=', $startDate)->count(),
            'patients_with_records' => $patients->filter(function ($patient) {
                return $patient->medicalRecords->count() > 0;
            })->count(),
            'patients_with_appointments' => $patients->filter(function ($patient) {
                return $patient->appointments->count() > 0;
            })->count()
        ];
    }

    /**
     * Generate diagnosis report
     */
    private function generateDiagnosisReport($filters, $startDate, $endDate)
    {
        $diagnoses = Diagnosis::with(['patient', 'doctor', 'disease'])
            ->whereBetween('diagnosis_date', [$startDate, $endDate])
            ->get();

        return [
            'total_diagnoses' => $diagnoses->count(),
            'confirmed_diagnoses' => $diagnoses->where('status', 'confirmed')->count(),
            'tentative_diagnoses' => $diagnoses->where('status', 'tentative')->count(),
            'confidence_distribution' => $diagnoses->groupBy('confidence_level')->map->count(),
            'status_distribution' => $diagnoses->groupBy('status')->map->count()
        ];
    }

    /**
     * Generate treatment report
     */
    private function generateTreatmentReport($filters, $startDate, $endDate)
    {
        $treatments = Treatment::with(['patient', 'doctor'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get();

        return [
            'total_treatments' => $treatments->count(),
            'active_treatments' => $treatments->where('status', 'active')->count(),
            'completed_treatments' => $treatments->where('status', 'completed')->count(),
            'treatment_types' => $treatments->groupBy('treatment_type')->map->count()
        ];
    }

    /**
     * Generate appointment report
     */
    private function generateAppointmentReport($filters, $startDate, $endDate)
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->get();

        return [
            'total_appointments' => $appointments->count(),
            'scheduled_appointments' => $appointments->where('status', 'scheduled')->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
            'cancelled_appointments' => $appointments->where('status', 'cancelled')->count(),
            'appointment_types' => $appointments->groupBy('type')->map->count()
        ];
    }

    /**
     * Generate expert system report
     */
    private function generateExpertSystemReport($filters, $startDate, $endDate)
    {
        $diagnoses = Diagnosis::with(['disease'])
            ->whereBetween('diagnosis_date', [$startDate, $endDate])
            ->get();

        return [
            'total_analyses' => $diagnoses->count(),
            'confidence_distribution' => $diagnoses->groupBy('confidence_level')->map->count(),
            'disease_accuracy' => $diagnoses->groupBy('disease_id')->map(function ($group) {
                $disease = $group->first()->disease;
                return [
                    'disease_name' => $disease->name,
                    'total_cases' => $group->count(),
                    'high_confidence' => $group->whereIn('confidence_level', ['very_strong', 'strong'])->count()
                ];
            })
        ];
    }

    /**
     * Generate custom report
     */
    private function generateCustomReport($filters, $startDate, $endDate)
    {
        return [
            'message' => 'Custom report generation not yet implemented',
            'filters' => $filters,
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ];
    }
}