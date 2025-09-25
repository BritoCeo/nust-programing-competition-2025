<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view medical records');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        // Admin can view all records
        if ($user->hasRole('admin')) {
            return true;
        }

        // Patients can only view their own records
        if ($user->hasRole('patient')) {
            return $medicalRecord->patient_id === $user->id;
        }

        // Doctors can view records they created or for their patients
        if ($user->hasRole('doctor')) {
            return $medicalRecord->doctor_id === $user->id || 
                   $medicalRecord->patient_id === $user->id;
        }

        // Nurses can view records for patients they're assigned to
        if ($user->hasRole('nurse')) {
            return $medicalRecord->patient_id === $user->id;
        }

        // Pharmacists can view records for prescription purposes
        if ($user->hasRole('pharmacist')) {
            return true; // Pharmacists need access for drug administration
        }

        // Receptionists can view records for administrative purposes
        if ($user->hasRole('receptionist')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create medical records');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        // Admin can update all records
        if ($user->hasRole('admin')) {
            return true;
        }

        // Only the doctor who created the record can update it
        if ($user->hasRole('doctor')) {
            return $medicalRecord->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        // Only admin can delete records
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->hasRole('admin');
    }
}