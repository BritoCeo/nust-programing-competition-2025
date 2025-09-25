<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'emergency_contact',
        'medical_license_number',
        'specialization',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's role name
     */
    public function getRoleNameAttribute(): string
    {
        return $this->roles->first()?->name ?? 'patient';
    }

    /**
     * Check if user is a medical professional
     */
    public function isMedicalProfessional(): bool
    {
        return $this->hasAnyRole(['doctor', 'nurse', 'pharmacist']);
    }

    /**
     * Check if user is a patient
     */
    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    /**
     * Get user's medical records (if patient)
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    /**
     * Get user's appointments (if patient)
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Get doctor's patients (if doctor)
     */
    public function patients()
    {
        return $this->hasManyThrough(
            User::class,
            MedicalRecord::class,
            'doctor_id',
            'id',
            'id',
            'patient_id'
        );
    }
}
