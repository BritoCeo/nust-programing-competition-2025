<?php

namespace App\Http\Controllers;

use App\Services\ExpertSystemService;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Diagnosis;
use App\Models\ExpertSystemRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpertSystemController extends Controller
{
    protected $expertSystemService;

    public function __construct(ExpertSystemService $expertSystemService)
    {
        $this->middleware('permission:use expert system');
        $this->expertSystemService = $expertSystemService;
    }

    /**
     * Display the expert system interface
     */
    public function index()
    {
        $symptoms = Symptom::orderBy('category')->orderBy('name')->get();
        $diseases = Disease::all();
        $statistics = $this->expertSystemService->getSystemStatistics();
        
        return view('expert-system.index', compact('symptoms', 'diseases', 'statistics'));
    }

    /**
     * Analyze symptoms and provide diagnosis
     */
    public function analyze(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'symptoms' => 'required|array|min:1|max:20',
            'symptoms.*' => 'required|integer|exists:symptoms,id',
            'clinical_notes' => 'nullable|string|max:1000',
            'patient_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Validate symptoms
            $validationErrors = $this->expertSystemService->validateSymptoms($request->symptoms);
            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['symptoms' => $validationErrors],
                ], 422);
            }

            // Perform analysis
            $analysis = $this->expertSystemService->analyzeSymptoms(
                $request->symptoms,
                $request->patient_id,
                Auth::id()
            );

            // Add clinical notes if provided
            $additionalData = [];
            if ($request->filled('clinical_notes')) {
                $additionalData['clinical_notes'] = $request->clinical_notes;
            }

            // Create diagnosis record
            $diagnosis = $this->expertSystemService->createDiagnosis($analysis, $additionalData);

            return response()->json([
                'success' => true,
                'analysis' => $analysis,
                'diagnosis_id' => $diagnosis->id,
                'message' => 'Analysis completed successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analysis failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get symptoms by category
     */
    public function getSymptomsByCategory(Request $request)
    {
        $category = $request->get('category', 'all');
        
        $query = Symptom::orderBy('name');
        
        if ($category !== 'all') {
            $query->where('category', $category);
        }
        
        $symptoms = $query->get();
        
        return response()->json([
            'success' => true,
            'symptoms' => $symptoms,
        ]);
    }

    /**
     * Get disease information
     */
    public function getDiseaseInfo(Request $request)
    {
        $diseaseId = $request->get('disease_id');
        
        $disease = Disease::find($diseaseId);
        
        if (!$disease) {
            return response()->json([
                'success' => false,
                'message' => 'Disease not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'disease' => $disease,
        ]);
    }

    /**
     * Get expert system rules
     */
    public function getRules()
    {
        $rules = ExpertSystemRule::with('disease')
            ->active()
            ->orderBy('priority_order')
            ->get();
        
        return response()->json([
            'success' => true,
            'rules' => $rules,
        ]);
    }

    /**
     * Get diagnosis history for a patient
     */
    public function getPatientDiagnoses(Request $request)
    {
        $patientId = $request->get('patient_id');
        
        $diagnoses = Diagnosis::where('patient_id', $patientId)
            ->with(['disease', 'doctor'])
            ->orderBy('diagnosis_date', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'diagnoses' => $diagnoses,
        ]);
    }

    /**
     * Update diagnosis status
     */
    public function updateDiagnosisStatus(Request $request, Diagnosis $diagnosis)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:tentative,confirmed,ruled_out,follow_up',
            'clinical_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $diagnosis->update([
            'status' => $request->status,
            'clinical_notes' => $request->clinical_notes ?? $diagnosis->clinical_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Diagnosis status updated successfully',
            'diagnosis' => $diagnosis->fresh(),
        ]);
    }

    /**
     * Get expert system statistics
     */
    public function getStatistics()
    {
        $statistics = $this->expertSystemService->getSystemStatistics();
        
        return response()->json([
            'success' => true,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Export diagnosis report
     */
    public function exportReport(Diagnosis $diagnosis)
    {
        $diagnosis->load(['patient', 'doctor', 'disease']);
        
        $data = [
            'diagnosis' => $diagnosis,
            'analysis' => $diagnosis->expert_system_analysis,
            'generated_at' => now(),
        ];
        
        // This would typically generate a PDF or other format
        // For now, return JSON data
        return response()->json([
            'success' => true,
            'report_data' => $data,
        ]);
    }
}
