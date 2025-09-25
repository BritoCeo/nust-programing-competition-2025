<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Drug extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'drug_code',
        'category',
        'dosage_form',
        'strength',
        'indications',
        'contraindications',
        'side_effects',
        'interactions',
        'storage_conditions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the drug administrations for this drug
     */
    public function drugAdministrations(): HasMany
    {
        return $this->hasMany(DrugAdministration::class);
    }

    /**
     * Scope for active drugs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for drugs by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get drug interactions as array
     */
    public function getInteractionsArrayAttribute(): array
    {
        return $this->interactions ? explode(',', $this->interactions) : [];
    }

    /**
     * Get side effects as array
     */
    public function getSideEffectsArrayAttribute(): array
    {
        return $this->side_effects ? explode(',', $this->side_effects) : [];
    }

    /**
     * Check if drug has interactions with another drug
     */
    public function hasInteractionWith(string $drugName): bool
    {
        $interactions = $this->getInteractionsArrayAttribute();
        return in_array($drugName, $interactions);
    }

    /**
     * Get recommended dosage for age group
     */
    public function getRecommendedDosage(string $ageGroup): string
    {
        return match ($ageGroup) {
            'adult' => $this->strength,
            'child' => $this->getChildDosage(),
            'elderly' => $this->getElderlyDosage(),
            default => $this->strength,
        };
    }

    /**
     * Get child dosage (typically 50% of adult)
     */
    private function getChildDosage(): string
    {
        $strength = (float) $this->strength;
        return ($strength * 0.5) . 'mg';
    }

    /**
     * Get elderly dosage (typically 75% of adult)
     */
    private function getElderlyDosage(): string
    {
        $strength = (float) $this->strength;
        return ($strength * 0.75) . 'mg';
    }
}
