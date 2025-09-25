<?php

namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get disease trend analysis
     */
    public function getDiseaseTrends(int $months = 12): array
    {
        $startDate = Carbon::now()->subMonths($months);
        
        $diseaseTrends = Diagnosis::select([
                'diseases.name as disease_name',
                DB::raw('COUNT(diagnoses.id) as diagnosis_count'),
                DB::raw('MONTH(diagnoses.created_at) as month'),
                DB::raw('YEAR(diagnoses.created_at) as year')
            ])
            ->join('diseases', 'diagnoses.disease_id', '=', 'diseases.id')
            ->where('diagnoses.created_at', '>=', $startDate)
            ->groupBy('diseases.name', 'month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $trends = [];
        foreach ($diseaseTrends as $trend) {
            $key = $trend->disease_name;
            if (!isset($trends[$key])) {
                $trends[$key] = [];
            }
            $trends[$key][] = [
                'month' => $trend->month,
                'year' => $trend->year,
                'count' => $trend->diagnosis_count
            ];
        }

        return $trends;
    }

    /**
     * Get patient outcome tracking
     */
    public function getPatientOutcomes(int $months = 6): array
    {
        $startDate = Carbon::now()->subMonths($months);
        
        $outcomes = MedicalRecord::select([
                DB::raw('COUNT(*) as total_records'),
                DB::raw('COUNT(CASE WHEN treatment_plan IS NOT NULL THEN 1 END) as treated_patients'),
                DB::raw('COUNT(CASE WHEN follow_up_date IS NOT NULL THEN 1 END) as follow_up_scheduled'),
                DB::raw('AVG(CASE WHEN follow_up_date IS NOT NULL THEN DATEDIFF(follow_up_date, created_at) END) as avg_follow_up_days')
            ])
            ->where('created_at', '>=', $startDate)
            ->first();

        $appointmentStats = Appointment::select([
                DB::raw('COUNT(*) as total_appointments'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_appointments'),
                DB::raw('COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled_appointments'),
                DB::raw('AVG(CASE WHEN status = "completed" THEN DATEDIFF(updated_at, created_at) END) as avg_treatment_duration')
            ])
            ->where('created_at', '>=', $startDate)
            ->first();

        return [
            'medical_records' => $outcomes,
            'appointments' => $appointmentStats,
            'success_rate' => $outcomes->total_records > 0 
                ? round(($outcomes->treated_patients / $outcomes->total_records) * 100, 2) 
                : 0,
            'follow_up_rate' => $outcomes->total_records > 0 
                ? round(($outcomes->follow_up_scheduled / $outcomes->total_records) * 100, 2) 
                : 0
        ];
    }

    /**
     * Get treatment effectiveness metrics
     */
    public function getTreatmentEffectiveness(): array
    {
        $treatments = DB::table('treatments')
            ->select([
                'treatment_name',
                DB::raw('COUNT(*) as total_treatments'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as successful_treatments'),
                DB::raw('COUNT(CASE WHEN status = "active" THEN 1 END) as active_treatments'),
                DB::raw('AVG(CASE WHEN end_date IS NOT NULL THEN DATEDIFF(end_date, start_date) END) as avg_duration_days')
            ])
            ->groupBy('treatment_name')
            ->get();

        $effectiveness = [];
        foreach ($treatments as $treatment) {
            $successRate = $treatment->total_treatments > 0 
                ? round(($treatment->successful_treatments / $treatment->total_treatments) * 100, 2) 
                : 0;

            $effectiveness[] = [
                'treatment_name' => $treatment->treatment_name,
                'total_treatments' => $treatment->total_treatments,
                'successful_treatments' => $treatment->successful_treatments,
                'active_treatments' => $treatment->active_treatments,
                'success_rate' => $successRate,
                'avg_duration_days' => round($treatment->avg_duration_days ?? 0, 1)
            ];
        }

        return $effectiveness;
    }

    /**
     * Get predictive analytics
     */
    public function getPredictiveAnalytics(): array
    {
        // Disease prediction based on symptoms
        $symptomDiseaseCorrelation = DB::table('diagnosis_symptom')
            ->join('symptoms', 'diagnosis_symptom.symptom_id', '=', 'symptoms.id')
            ->join('diagnoses', 'diagnosis_symptom.diagnosis_id', '=', 'diagnoses.id')
            ->join('diseases', 'diagnoses.disease_id', '=', 'diseases.id')
            ->select([
                'symptoms.name as symptom_name',
                'diseases.name as disease_name',
                DB::raw('COUNT(*) as correlation_count')
            ])
            ->groupBy('symptoms.name', 'diseases.name')
            ->orderBy('correlation_count', 'desc')
            ->limit(20)
            ->get();

        // Seasonal disease patterns
        $seasonalPatterns = Diagnosis::select([
                'diseases.name as disease_name',
                DB::raw('MONTH(diagnoses.created_at) as month'),
                DB::raw('COUNT(*) as diagnosis_count')
            ])
            ->join('diseases', 'diagnoses.disease_id', '=', 'diseases.id')
            ->where('diagnoses.created_at', '>=', Carbon::now()->subYear())
            ->groupBy('diseases.name', 'month')
            ->orderBy('month')
            ->get();

        // Risk factors analysis
        $riskFactors = MedicalRecord::select([
                'chief_complaint',
                DB::raw('COUNT(*) as frequency'),
                DB::raw('AVG(age) as avg_age')
            ])
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('chief_complaint')
            ->orderBy('frequency', 'desc')
            ->limit(10)
            ->get();

        return [
            'symptom_disease_correlation' => $symptomDiseaseCorrelation,
            'seasonal_patterns' => $seasonalPatterns,
            'risk_factors' => $riskFactors,
            'predictions' => $this->generatePredictions($symptomDiseaseCorrelation, $seasonalPatterns)
        ];
    }

    /**
     * Get system statistics
     */
    public function getSystemStatistics(): array
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'doctors' => User::role('doctor')->count(),
                'nurses' => User::role('nurse')->count(),
                'patients' => User::role('patient')->count(),
                'active_this_month' => User::where('created_at', '>=', Carbon::now()->subMonth())->count()
            ],
            'medical_data' => [
                'total_records' => MedicalRecord::count(),
                'total_appointments' => Appointment::count(),
                'total_diagnoses' => Diagnosis::count(),
                'total_treatments' => DB::table('treatments')->count()
            ],
            'diseases' => [
                'total_diseases' => Disease::count(),
                'total_symptoms' => Symptom::count(),
                'active_diseases' => Disease::where('is_active', true)->count()
            ],
            'blog' => [
                'total_posts' => BlogPost::count(),
                'published_posts' => BlogPost::where('status', 'published')->count(),
                'total_views' => BlogPost::sum('views_count'),
                'total_likes' => DB::table('blog_likes')->count(),
                'total_comments' => DB::table('blog_comments')->count()
            ]
        ];

        return $stats;
    }

    /**
     * Get dashboard analytics
     */
    public function getDashboardAnalytics(): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->subWeek();
        $thisMonth = Carbon::now()->subMonth();

        return [
            'today' => [
                'appointments' => Appointment::whereDate('appointment_date', $today)->count(),
                'medical_records' => MedicalRecord::whereDate('created_at', $today)->count(),
                'diagnoses' => Diagnosis::whereDate('created_at', $today)->count()
            ],
            'this_week' => [
                'appointments' => Appointment::where('created_at', '>=', $thisWeek)->count(),
                'medical_records' => MedicalRecord::where('created_at', '>=', $thisWeek)->count(),
                'diagnoses' => Diagnosis::where('created_at', '>=', $thisWeek)->count()
            ],
            'this_month' => [
                'appointments' => Appointment::where('created_at', '>=', $thisMonth)->count(),
                'medical_records' => MedicalRecord::where('created_at', '>=', $thisMonth)->count(),
                'diagnoses' => Diagnosis::where('created_at', '>=', $thisMonth)->count()
            ],
            'trends' => $this->getTrends($thisMonth),
            'top_diseases' => $this->getTopDiseases($thisMonth),
            'top_symptoms' => $this->getTopSymptoms($thisMonth)
        ];
    }

    /**
     * Get trends data
     */
    private function getTrends(Carbon $startDate): array
    {
        $appointments = Appointment::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $medicalRecords = MedicalRecord::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'appointments' => $appointments,
            'medical_records' => $medicalRecords
        ];
    }

    /**
     * Get top diseases
     */
    private function getTopDiseases(Carbon $startDate): array
    {
        return Diagnosis::select([
                'diseases.name as disease_name',
                DB::raw('COUNT(*) as diagnosis_count')
            ])
            ->join('diseases', 'diagnoses.disease_id', '=', 'diseases.id')
            ->where('diagnoses.created_at', '>=', $startDate)
            ->groupBy('diseases.name')
            ->orderBy('diagnosis_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Get top symptoms
     */
    private function getTopSymptoms(Carbon $startDate): array
    {
        return DB::table('diagnosis_symptom')
            ->join('symptoms', 'diagnosis_symptom.symptom_id', '=', 'symptoms.id')
            ->join('diagnoses', 'diagnosis_symptom.diagnosis_id', '=', 'diagnoses.id')
            ->select([
                'symptoms.name as symptom_name',
                DB::raw('COUNT(*) as frequency')
            ])
            ->where('diagnoses.created_at', '>=', $startDate)
            ->groupBy('symptoms.name')
            ->orderBy('frequency', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Generate predictions based on data
     */
    private function generatePredictions($correlations, $seasonalPatterns): array
    {
        $predictions = [];
        
        // Predict likely diseases based on current season
        $currentMonth = Carbon::now()->month;
        $seasonalDiseases = $seasonalPatterns->where('month', $currentMonth);
        
        if ($seasonalDiseases->isNotEmpty()) {
            $predictions['seasonal_diseases'] = $seasonalDiseases->take(3)->values();
        }

        // Predict high-risk symptoms
        $highRiskSymptoms = $correlations->where('correlation_count', '>', 5);
        if ($highRiskSymptoms->isNotEmpty()) {
            $predictions['high_risk_symptoms'] = $highRiskSymptoms->take(5)->values();
        }

        return $predictions;
    }
}
