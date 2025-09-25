<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view appointments')->only(['index', 'show']);
        $this->middleware('permission:create appointments')->only(['create', 'store']);
        $this->middleware('permission:edit appointments')->only(['edit', 'update']);
        $this->middleware('permission:cancel appointments')->only(['cancel']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor', 'creator']);

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
                $q->where('appointment_number', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
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
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'type' => 'required|in:consultation,follow_up,emergency,routine_checkup,specialist',
            'reason' => 'required|string|max:1000',
            'notes' => 'nullable|string',
        ]);

        // Check for appointment conflicts
        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $startTime = Carbon::parse($request->appointment_time);
                $endTime = $startTime->addMinutes($request->duration_minutes ?? 30);
                
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->whereBetween('appointment_time', [$startTime, $endTime])
                      ->orWhereBetween('appointment_time', [
                          $startTime->subMinutes(30),
                          $endTime->addMinutes(30)
                      ]);
                });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot conflicts with an existing appointment.']);
        }

        $data = $request->all();
        $data['appointment_number'] = Appointment::generateAppointmentNumber();
        $data['created_by'] = Auth::id();
        $data['status'] = 'scheduled';

        $appointment = Appointment::create($data);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        $appointment->load(['patient', 'doctor', 'creator']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'type' => 'required|in:consultation,follow_up,emergency,routine_checkup,specialist',
            'reason' => 'required|string|max:1000',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
        ]);

        $appointment->update($request->all());

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Get today's appointments
     */
    public function today()
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor'])->today();

        if ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        $appointments = $query->orderBy('appointment_time')->get();

        return view('appointments.today', compact('appointments'));
    }

    /**
     * Get upcoming appointments
     */
    public function upcoming()
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor'])->upcoming();

        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        $appointments = $query->orderBy('appointment_date')->get();

        return view('appointments.upcoming', compact('appointments'));
    }
}
