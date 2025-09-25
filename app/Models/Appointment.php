<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_number',
        'appointment_date',
        'appointment_time',
        'duration_minutes',
        'status',
        'type',
        'reason',
        'notes',
        'cancellation_reason',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
    ];

    /**
     * Get the patient for this appointment
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor for this appointment
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the user who created this appointment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate unique appointment number
     */
    public static function generateAppointmentNumber(): string
    {
        $prefix = 'APT';
        $date = now()->format('Ymd');
        $lastAppointment = self::whereDate('created_at', today())->count();
        $sequence = str_pad($lastAppointment + 1, 4, '0', STR_PAD_LEFT);
        
        return $prefix . $date . $sequence;
    }

    /**
     * Check if appointment is today
     */
    public function isToday(): bool
    {
        return $this->appointment_date->isToday();
    }

    /**
     * Check if appointment is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->appointment_date->isFuture() && $this->status === 'scheduled';
    }

    /**
     * Check if appointment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->appointment_date->isPast() && in_array($this->status, ['scheduled', 'confirmed']);
    }

    /**
     * Scope for today's appointments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Scope for upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
                    ->where('status', 'scheduled');
    }

    /**
     * Scope for appointments by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for appointments by doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope for appointments by patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
