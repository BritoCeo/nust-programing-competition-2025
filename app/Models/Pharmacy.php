<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'license_number',
        'pharmacist_id',
        'operating_hours',
        'is_active',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the pharmacist who manages this pharmacy
     */
    public function pharmacist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    /**
     * Get the drug administrations for this pharmacy
     */
    public function drugAdministrations(): HasMany
    {
        return $this->hasMany(DrugAdministration::class);
    }

    /**
     * Check if pharmacy is open at given time
     */
    public function isOpenAt($time = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $time = $time ?? now();
        $dayOfWeek = strtolower($time->format('l'));
        
        if (!isset($this->operating_hours[$dayOfWeek])) {
            return false;
        }

        $hours = $this->operating_hours[$dayOfWeek];
        $currentTime = $time->format('H:i');
        
        return $currentTime >= $hours['open'] && $currentTime <= $hours['close'];
    }

    /**
     * Get operating hours for a specific day
     */
    public function getOperatingHoursForDay($day): ?array
    {
        $day = strtolower($day);
        return $this->operating_hours[$day] ?? null;
    }

    /**
     * Scope for active pharmacies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for pharmacies by location
     */
    public function scopeByLocation($query, $address)
    {
        return $query->where('address', 'like', "%{$address}%");
    }
}
