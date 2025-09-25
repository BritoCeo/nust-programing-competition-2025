<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view treatments')->only(['index', 'show']);
        $this->middleware('permission:create treatments')->only(['create', 'store']);
        $this->middleware('permission:edit treatments')->only(['edit', 'update']);
        $this->middleware('permission:delete treatments')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Treatment::with(['patient', 'doctor']);

        // Role-based filtering
        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        // Search and filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('treatment_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('doctor', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('treatment_type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        $treatments = $query->orderBy('start_date', 'desc')->paginate(15);

        return view('treatments.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('treatments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'treatment_name' => 'required|string|max:255',
            'treatment_type' => 'required|in:medication,therapy,surgery,lifestyle,monitoring',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'frequency' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after_or_equal:start_date',
            'follow_up_notes' => 'nullable|string',
            'medications' => 'nullable|array',
            'medications.*.name' => 'required_with:medications|string|max:255',
            'medications.*.dosage' => 'required_with:medications|string|max:255',
            'medications.*.instructions' => 'required_with:medications|string|max:500',
        ]);

        $data = $request->all();
        $data['status'] = 'active';
        $data['created_by'] = Auth::id();

        // Handle medications
        if ($request->has('medications')) {
            $data['medications'] = array_filter($request->medications, function($med) {
                return !empty($med['name']);
            });
        }

        $treatment = Treatment::create($data);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', 'Treatment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        $this->authorize('view', $treatment);
        
        $treatment->load(['patient', 'doctor', 'creator']);
        
        return view('treatments.show', compact('treatment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Treatment $treatment)
    {
        $this->authorize('update', $treatment);
        
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('treatments.edit', compact('treatment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $this->authorize('update', $treatment);

        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'treatment_name' => 'required|string|max:255',
            'treatment_type' => 'required|in:medication,therapy,surgery,lifestyle,monitoring',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'frequency' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after_or_equal:start_date',
            'follow_up_notes' => 'nullable|string',
            'status' => 'required|in:active,completed,discontinued,pending',
            'medications' => 'nullable|array',
            'medications.*.name' => 'required_with:medications|string|max:255',
            'medications.*.dosage' => 'required_with:medications|string|max:255',
            'medications.*.instructions' => 'required_with:medications|string|max:500',
        ]);

        $data = $request->all();

        // Handle medications
        if ($request->has('medications')) {
            $data['medications'] = array_filter($request->medications, function($med) {
                return !empty($med['name']);
            });
        }

        $treatment->update($data);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', 'Treatment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        $this->authorize('delete', $treatment);

        $treatment->delete();

        return redirect()->route('treatments.index')
            ->with('success', 'Treatment deleted successfully.');
    }
}