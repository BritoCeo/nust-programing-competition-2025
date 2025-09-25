<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertSystemRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'disease_id',
        'required_symptoms',
        'optional_symptoms',
        'min_symptoms_required',
        'confidence_level',
        'priority_order',
        'rule_description',
        'requires_xray',
        'is_active',
    ];

    protected $casts = [
        'required_symptoms' => 'array',
        'optional_symptoms' => 'array',
        'requires_xray' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the disease for this rule
     */
    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Scope for active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for rules by confidence level
     */
    public function scopeByConfidenceLevel($query, $level)
    {
        return $query->where('confidence_level', $level);
    }
}
