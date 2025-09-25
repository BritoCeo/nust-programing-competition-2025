<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor']);

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
                $q->where('reason', 'like', "%{$search}%")
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
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);

        // Get statistics for the dashboard
        $stats = [
            'total_appointments' => $query->count(),
            'today_appointments' => $query->whereDate('appointment_date', today())->count(),
            'upcoming_appointments' => $query->where('appointment_date', '>=', today())->where('status', 'scheduled')->count(),
            'completed_appointments' => $query->where('status', 'completed')->count(),
            'cancelled_appointments' => $query->where('status', 'cancelled')->count(),
        ];

        // Get today's appointments
        $todayAppointments = $query->whereDate('appointment_date', today())->orderBy('appointment_time')->get();

        // Get upcoming appointments
        $upcomingAppointments = $query->where('appointment_date', '>', today())->orderBy('appointment_date')->orderBy('appointment_time')->limit(5)->get();

        return view('appointments.index', compact('appointments', 'stats', 'todayAppointments', 'upcomingAppointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:1000',
            'type' => 'required|in:consultation,follow_up,emergency,checkup',
            'notes' => 'nullable|string|max:2000',
            'duration' => 'nullable|integer|min:15|max:240',
            'priority' => 'nullable|in:low,medium,high,urgent'
        ], [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.after' => 'Appointment date must be in the future.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Please enter a valid time format (HH:MM).',
            'reason.required' => 'Appointment reason is required.',
            'reason.max' => 'Reason must not exceed 1000 characters.',
            'type.required' => 'Please select an appointment type.',
            'type.in' => 'Invalid appointment type selected.',
            'notes.max' => 'Notes must not exceed 2000 characters.',
            'duration.min' => 'Appointment duration must be at least 15 minutes.',
            'duration.max' => 'Appointment duration must not exceed 240 minutes.',
            'priority.in' => 'Invalid priority level selected.'
        ]);

        // Check for time conflicts
        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.']);
        }

        // Check if doctor is available at the requested time
        $doctor = User::find($request->doctor_id);
        if (!$doctor || !$doctor->hasRole('doctor')) {
            return back()->withErrors(['doctor_id' => 'The selected user is not a doctor.']);
        }

        try {
            $appointment = Appointment::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'reason' => $request->reason,
                'type' => $request->type,
                'notes' => $request->notes,
                'duration' => $request->duration ?? 30,
                'priority' => $request->priority ?? 'medium',
                'status' => 'scheduled'
            ]);

            // Log the activity
            activity()
                ->performedOn($appointment)
                ->causedBy(Auth::user())
                ->log('Appointment scheduled');

            // Send notification to patient and doctor (if notification system is implemented)
            // $this->sendAppointmentNotification($appointment);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment scheduled successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while scheduling the appointment. Please try again.']);
        }
    }

    /**
     * Display the specified appointment
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        $appointment->load(['patient', 'doctor']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:1000',
            'type' => 'required|in:consultation,follow_up,emergency,checkup',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        // Check for time conflicts (excluding current appointment)
        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.']);
        }

        $appointment->update($request->only([
            'patient_id', 'doctor_id', 'appointment_date', 
            'appointment_time', 'reason', 'type', 'status', 'notes'
        ]));

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $appointment->update(['status' => 'cancelled']);
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Confirm appointment
     */
    public function confirm(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $appointment->update(['status' => 'confirmed']);
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment confirmed successfully.');
    }

    /**
     * Cancel appointment
     */
    public function cancel(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $appointment->update(['status' => 'cancelled']);
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Complete appointment
     */
    public function complete(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $appointment->update(['status' => 'completed']);
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment completed successfully.');
    }

    /**
     * Get today's appointments
     */
    public function today()
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today());

        // Apply role-based filtering
        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
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
        $query = Appointment::with(['patient', 'doctor'])
            ->where('appointment_date', '>=', today())
            ->where('status', '!=', 'cancelled');

        // Apply role-based filtering
        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        $appointments = $query->orderBy('appointment_date')->orderBy('appointment_time')->get();

        return view('appointments.upcoming', compact('appointments'));
    }

    /**
     * Get calendar view
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor']);

        // Apply role-based filtering
        if ($user->hasRole('patient')) {
            $query->where('patient_id', $user->id);
        } elseif ($user->hasRole('doctor')) {
            $query->where('doctor_id', $user->id);
        }

        // Get appointments for the requested month
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $appointments = $query->whereMonth('appointment_date', $month)
            ->whereYear('appointment_date', $year)
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('appointments.calendar', compact('appointments', 'month', 'year'));
    }
}
