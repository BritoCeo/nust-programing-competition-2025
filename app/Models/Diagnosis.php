<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'medical_record_id',
        'disease_id',
        'diagnosis_code',
        'symptoms_entered',
        'expert_system_analysis',
        'confidence_level',
        'requires_xray',
        'clinical_notes',
        'differential_diagnosis',
        'status',
        'diagnosis_date',
    ];

    protected $casts = [
        'symptoms_entered' => 'array',
        'expert_system_analysis' => 'array',
        'requires_xray' => 'boolean',
        'diagnosis_date' => 'date',
    ];

    /**
     * Get the patient for this diagnosis
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor for this diagnosis
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the medical record for this diagnosis
     */
    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    /**
     * Get the disease for this diagnosis
     */
    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Get the treatments for this diagnosis
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Check if diagnosis is tentative
     */
    public function isTentative(): bool
    {
        return $this->status === 'tentative';
    }

    /**
     * Check if diagnosis is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if diagnosis is ruled out
     */
    public function isRuledOut(): bool
    {
        return $this->status === 'ruled_out';
    }

    /**
     * Check if diagnosis requires follow-up
     */
    public function requiresFollowUp(): bool
    {
        return $this->status === 'follow_up';
    }

    /**
     * Get confidence percentage
     */
    public function getConfidencePercentageAttribute(): int
    {
        return match ($this->confidence_level) {
            'very_strong' => 95,
            'strong' => 80,
            'weak' => 60,
            'very_weak' => 40,
            default => 50,
        };
    }

    /**
     * Scope for confirmed diagnoses
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for tentative diagnoses
     */
    public function scopeTentative($query)
    {
        return $query->where('status', 'tentative');
    }

    /**
     * Scope for diagnoses by patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope for diagnoses by doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope for diagnoses by disease
     */
    public function scopeForDisease($query, $diseaseId)
    {
        return $query->where('disease_id', $diseaseId);
    }

    /**
     * Scope for high confidence diagnoses
     */
    public function scopeHighConfidence($query)
    {
        return $query->whereIn('confidence_level', ['very_strong', 'strong']);
    }
}
