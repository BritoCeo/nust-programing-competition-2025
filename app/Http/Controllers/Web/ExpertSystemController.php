<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Diagnosis;
use App\Models\ExpertSystemRule;
use App\Services\ExpertSystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertSystemController extends Controller
{
    protected $expertSystemService;

    public function __construct(ExpertSystemService $expertSystemService)
    {
        $this->expertSystemService = $expertSystemService;
    }

    /**
     * Display the expert system interface
     */
    public function index()
    {
        $symptoms = Symptom::with('category')->get()->groupBy('category.name');
        $diseases = Disease::all();
        
        return view('expert-system.index', compact('symptoms', 'diseases'));
    }

    /**
     * Analyze symptoms and get diagnosis
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'exists:symptoms,id',
            'patient_id' => 'nullable|exists:users,id',
            'additional_notes' => 'nullable|string|max:1000',
            'patient_age' => 'nullable|integer|min:0|max:120',
            'patient_gender' => 'nullable|in:male,female,other',
            'patient_weight' => 'nullable|numeric|min:1|max:500',
            'patient_height' => 'nullable|numeric|min:30|max:250'
        ]);

        $symptomIds = $request->symptoms;
        $patientId = $request->patient_id;
        $additionalNotes = $request->additional_notes;
        $patientData = [
            'age' => $request->patient_age,
            'gender' => $request->patient_gender,
            'weight' => $request->patient_weight,
            'height' => $request->patient_height
        ];

        try {
            // Get symptoms with details
            $symptoms = Symptom::whereIn('id', $symptomIds)->with('category')->get();
            
            // Get analysis from expert system service
            $analysis = $this->expertSystemService->analyzeMultiDiseaseSymptoms(
                $symptomIds, 
                $patientId, 
                Auth::id(),
                $additionalNotes,
                $patientData
            );

            // Store diagnosis if patient is specified and analysis is successful
            if ($patientId && $analysis['success']) {
                $diagnosis = Diagnosis::create([
                    'patient_id' => $patientId,
                    'doctor_id' => Auth::id(),
                    'symptoms' => $symptomIds,
                    'diagnosis' => $analysis['data']['primary_diagnosis']['disease_name'],
                    'confidence_score' => $analysis['data']['primary_diagnosis']['confidence_score'],
                    'treatment_plan' => $analysis['data']['treatment_plan'],
                    'notes' => $additionalNotes,
                    'patient_data' => $patientData,
                    'status' => 'tentative',
                    'analysis_data' => $analysis['data']
                ]);

                // Log the diagnosis activity
                activity()
                    ->performedOn($diagnosis)
                    ->causedBy(Auth::user())
                    ->log('AI diagnosis performed');
            }

            // Add symptoms details to response
            $analysis['symptoms_analyzed'] = $symptoms;
            $analysis['analysis_timestamp'] = now()->toISOString();

            return response()->json($analysis);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during diagnosis analysis.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get symptoms by category
     */
    public function getSymptomsByCategory(Request $request)
    {
        $category = $request->get('category');
        
        if ($category) {
            $symptoms = Symptom::whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            })->get();
        } else {
            $symptoms = Symptom::with('category')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $symptoms
        ]);
    }

    /**
     * Get disease information
     */
    public function getDiseaseInfo(Request $request)
    {
        $diseaseId = $request->get('disease_id');
        
        if (!$diseaseId) {
            return response()->json([
                'success' => false,
                'message' => 'Disease ID is required'
            ], 400);
        }

        $disease = Disease::find($diseaseId);
        
        if (!$disease) {
            return response()->json([
                'success' => false,
                'message' => 'Disease not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $disease
        ]);
    }

    /**
     * Get expert system rules
     */
    public function getRules(Request $request)
    {
        $rules = ExpertSystemRule::with(['disease', 'symptoms'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Get patient diagnoses
     */
    public function getPatientDiagnoses(Request $request, $patientId)
    {
        $diagnoses = Diagnosis::with(['disease', 'doctor'])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $diagnoses
        ]);
    }

    /**
     * Update diagnosis status
     */
    public function updateDiagnosisStatus(Request $request, Diagnosis $diagnosis)
    {
        $request->validate([
            'status' => 'required|in:tentative,confirmed,rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        $diagnosis->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Diagnosis status updated successfully',
            'data' => $diagnosis
        ]);
    }

    /**
     * Get expert system statistics
     */
    public function getStatistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_analyses' => Diagnosis::where('doctor_id', $user->id)->count(),
            'confirmed_diagnoses' => Diagnosis::where('doctor_id', $user->id)
                ->where('status', 'confirmed')->count(),
            'tentative_diagnoses' => Diagnosis::where('doctor_id', $user->id)
                ->where('status', 'tentative')->count(),
            'rejected_diagnoses' => Diagnosis::where('doctor_id', $user->id)
                ->where('status', 'rejected')->count(),
            'average_confidence' => Diagnosis::where('doctor_id', $user->id)
                ->avg('confidence_score'),
            'disease_distribution' => Diagnosis::where('doctor_id', $user->id)
                ->with('disease')
                ->get()
                ->groupBy('disease.name')
                ->map->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Export diagnosis report
     */
    public function exportReport(Diagnosis $diagnosis)
    {
        $this->authorize('view', $diagnosis);
        
        $diagnosis->load(['patient', 'doctor', 'disease']);
        
        // Generate PDF report (you can implement this based on your needs)
        // For now, return JSON data
        return response()->json([
            'success' => true,
            'data' => $diagnosis
        ]);
    }

    /**
     * Get all symptoms
     */
    public function getSymptoms()
    {
        $symptoms = Symptom::with('category')->get();
        
        return response()->json([
            'success' => true,
            'data' => $symptoms
        ]);
    }

    /**
     * Get all diseases
     */
    public function getDiseases()
    {
        $diseases = Disease::all();
        
        return response()->json([
            'success' => true,
            'data' => $diseases
        ]);
    }
}
