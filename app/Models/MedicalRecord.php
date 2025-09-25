<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'record_number',
        'visit_date',
        'chief_complaint',
        'history_of_present_illness',
        'past_medical_history',
        'family_history',
        'social_history',
        'vital_signs',
        'physical_examination',
        'assessment',
        'plan',
        'attachments',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'vital_signs' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the patient who owns the medical record
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor who created the medical record
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the appointments for this medical record
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the treatments for this medical record
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Generate unique record number
     */
    public static function generateRecordNumber(): string
    {
        $prefix = 'MR';
        $date = now()->format('Ymd');
        $lastRecord = self::whereDate('created_at', today())->count();
        $sequence = str_pad($lastRecord + 1, 4, '0', STR_PAD_LEFT);
        
        return $prefix . $date . $sequence;
    }

    /**
     * Scope for active records
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for records by patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope for records by doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
