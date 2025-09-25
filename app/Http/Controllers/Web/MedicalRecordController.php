<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of medical records
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = MedicalRecord::with(['patient', 'doctor', 'attachments']);

        // Apply role-based filtering
        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('record_number', 'like', "%{$search}%")
                  ->orWhere('chief_complaint', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($patientQuery) use ($search) {
                      $patientQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                      $doctorQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $medicalRecords = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics for the dashboard
        $stats = [
            'total_records' => $query->count(),
            'active_records' => $query->where('status', 'active')->count(),
            'archived_records' => $query->where('status', 'archived')->count(),
            'this_month' => $query->whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent records for quick access
        $recentRecords = $query->orderBy('created_at', 'desc')->limit(5)->get();

        return view('medical-records.index', compact('medicalRecords', 'stats', 'recentRecords'));
    }

    /**
     * Show the form for creating a new medical record
     */
    public function create()
    {
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('medical-records.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created medical record
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'record_number' => 'required|string|max:255|unique:medical_records',
            'chief_complaint' => 'required|string|max:1000',
            'history_of_present_illness' => 'nullable|string',
            'physical_examination' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'vital_signs.temperature' => 'nullable|numeric|min:30|max:45',
            'vital_signs.blood_pressure_systolic' => 'nullable|integer|min:60|max:250',
            'vital_signs.blood_pressure_diastolic' => 'nullable|integer|min:40|max:150',
            'vital_signs.heart_rate' => 'nullable|integer|min:40|max:200',
            'vital_signs.respiratory_rate' => 'nullable|integer|min:8|max:40',
            'vital_signs.oxygen_saturation' => 'nullable|numeric|min:70|max:100',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'
        ], [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'record_number.required' => 'Record number is required.',
            'record_number.unique' => 'This record number already exists.',
            'chief_complaint.required' => 'Chief complaint is required.',
            'chief_complaint.max' => 'Chief complaint must not exceed 1000 characters.',
            'vital_signs.temperature.min' => 'Temperature must be at least 30°C.',
            'vital_signs.temperature.max' => 'Temperature must not exceed 45°C.',
            'vital_signs.blood_pressure_systolic.min' => 'Systolic pressure must be at least 60.',
            'vital_signs.blood_pressure_systolic.max' => 'Systolic pressure must not exceed 250.',
            'attachments.max' => 'You can upload a maximum of 5 files.',
            'attachments.*.max' => 'Each file must not exceed 10MB.',
            'attachments.*.mimes' => 'Files must be PDF, JPG, PNG, DOC, or DOCX format.'
        ]);

        try {
            $medicalRecord = MedicalRecord::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => Auth::id(),
                'record_number' => $request->record_number,
                'chief_complaint' => $request->chief_complaint,
                'history_of_present_illness' => $request->history_of_present_illness,
                'physical_examination' => $request->physical_examination,
                'diagnosis' => $request->diagnosis,
                'treatment_plan' => $request->treatment_plan,
                'vital_signs' => $request->vital_signs,
                'status' => 'active'
            ]);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('medical-records/' . $medicalRecord->id, 'public');
                    $medicalRecord->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]);
                }
            }

            // Log the activity
            activity()
                ->performedOn($medicalRecord)
                ->causedBy(Auth::user())
                ->log('Medical record created');

            return redirect()->route('medical-records.index')
                ->with('success', 'Medical record created successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the medical record. Please try again.']);
        }
    }

    /**
     * Display the specified medical record
     */
    public function show(MedicalRecord $medicalRecord)
    {
        $this->authorize('view', $medicalRecord);
        
        $medicalRecord->load(['patient', 'doctor', 'attachments']);
        
        return view('medical-records.show', compact('medicalRecord'));
    }

    /**
     * Show the form for editing the specified medical record
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);
        
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('medical-records.edit', compact('medicalRecord', 'patients', 'doctors'));
    }

    /**
     * Update the specified medical record
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);

        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'record_number' => 'required|string|max:255|unique:medical_records,record_number,' . $medicalRecord->id,
            'chief_complaint' => 'required|string|max:1000',
            'history_of_present_illness' => 'nullable|string',
            'physical_examination' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'status' => 'required|in:active,archived,deleted',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'
        ]);

        $medicalRecord->update($request->only([
            'patient_id', 'record_number', 'chief_complaint', 
            'history_of_present_illness', 'physical_examination',
            'diagnosis', 'treatment_plan', 'status'
        ]));

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('medical-records/' . $medicalRecord->id, 'public');
                $medicalRecord->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);
            }
        }

        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record updated successfully.');
    }

    /**
     * Remove the specified medical record
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        $this->authorize('delete', $medicalRecord);
        
        $medicalRecord->update(['status' => 'deleted']);
        
        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record deleted successfully.');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(MedicalRecord $medicalRecord, $index)
    {
        $this->authorize('view', $medicalRecord);
        
        $attachment = $medicalRecord->attachments()->skip($index)->first();
        
        if (!$attachment) {
            abort(404, 'Attachment not found');
        }
        
        return Storage::disk('public')->download($attachment->file_path, $attachment->filename);
    }

    /**
     * Archive medical record
     */
    public function archive(MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);
        
        $medicalRecord->update(['status' => 'archived']);
        
        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record archived successfully.');
    }

    /**
     * Restore archived medical record
     */
    public function restore(MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);
        
        $medicalRecord->update(['status' => 'active']);
        
        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record restored successfully.');
    }
}
