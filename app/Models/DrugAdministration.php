<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrugAdministration extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'treatment_id',
        'pharmacy_id',
        'drug_name',
        'drug_code',
        'dosage',
        'frequency',
        'quantity_prescribed',
        'quantity_dispensed',
        'prescription_date',
        'dispense_date',
        'expiry_date',
        'administration_instructions',
        'side_effects_notes',
        'status',
        'prescribed_by',
        'dispensed_by',
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'dispense_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the patient for this drug administration
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the treatment for this drug administration
     */
    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Get the pharmacy for this drug administration
     */
    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    /**
     * Get the doctor who prescribed this drug
     */
    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }

    /**
     * Get the pharmacist who dispensed this drug
     */
    public function dispenser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    /**
     * Check if drug is prescribed
     */
    public function isPrescribed(): bool
    {
        return $this->status === 'prescribed';
    }

    /**
     * Check if drug is dispensed
     */
    public function isDispensed(): bool
    {
        return $this->status === 'dispensed';
    }

    /**
     * Check if drug is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if drug is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if drug is discontinued
     */
    public function isDiscontinued(): bool
    {
        return $this->status === 'discontinued';
    }

    /**
     * Check if drug is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Get remaining quantity
     */
    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_prescribed - $this->quantity_dispensed;
    }

    /**
     * Scope for prescribed drugs
     */
    public function scopePrescribed($query)
    {
        return $query->where('status', 'prescribed');
    }

    /**
     * Scope for dispensed drugs
     */
    public function scopeDispensed($query)
    {
        return $query->where('status', 'dispensed');
    }

    /**
     * Scope for active drugs
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['dispensed', 'in_progress']);
    }

    /**
     * Scope for drugs by patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope for drugs by pharmacy
     */
    public function scopeForPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }

    /**
     * Scope for expired drugs
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', today());
    }
}
