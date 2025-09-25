<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Medical Records
            'view medical records',
            'create medical records',
            'edit medical records',
            'delete medical records',
            
            // Appointments
            'view appointments',
            'create appointments',
            'edit appointments',
            'cancel appointments',
            
            // Diagnoses
            'view diagnoses',
            'create diagnoses',
            'edit diagnoses',
            'delete diagnoses',
            
            // Treatments
            'view treatments',
            'create treatments',
            'edit treatments',
            'delete treatments',
            
            // Pharmacy
            'view pharmacy',
            'manage pharmacy',
            'dispense drugs',
            'view drug inventory',
            
            // Reports
            'view reports',
            'generate reports',
            'export reports',
            
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            
            // Expert System
            'use expert system',
            'view expert system',
            'manage expert system',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $this->createAdminRole();
        $this->createDoctorRole();
        $this->createNurseRole();
        $this->createPharmacistRole();
        $this->createReceptionistRole();
        $this->createPatientRole();
    }

    private function createAdminRole(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());
    }

    private function createDoctorRole(): void
    {
        $doctor = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctor->syncPermissions([
            'view medical records',
            'create medical records',
            'edit medical records',
            'view appointments',
            'create appointments',
            'edit appointments',
            'view diagnoses',
            'create diagnoses',
            'edit diagnoses',
            'view treatments',
            'create treatments',
            'edit treatments',
            'use expert system',
            'view expert system',
            'view reports',
            'generate reports',
        ]);
    }

    private function createNurseRole(): void
    {
        $nurse = Role::firstOrCreate(['name' => 'nurse', 'guard_name' => 'web']);
        $nurse->syncPermissions([
            'view medical records',
            'create medical records',
            'edit medical records',
            'view appointments',
            'create appointments',
            'edit appointments',
            'view diagnoses',
            'view treatments',
            'create treatments',
            'edit treatments',
            'view reports',
        ]);
    }

    private function createPharmacistRole(): void
    {
        $pharmacist = Role::firstOrCreate(['name' => 'pharmacist', 'guard_name' => 'web']);
        $pharmacist->syncPermissions([
            'view pharmacy',
            'manage pharmacy',
            'dispense drugs',
            'view drug inventory',
            'view treatments',
            'view reports',
        ]);
    }

    private function createReceptionistRole(): void
    {
        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'guard_name' => 'web']);
        $receptionist->syncPermissions([
            'view appointments',
            'create appointments',
            'edit appointments',
            'cancel appointments',
            'view users',
            'create users',
            'edit users',
            'view reports',
        ]);
    }

    private function createPatientRole(): void
    {
        $patient = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);
        $patient->syncPermissions([
            'view medical records',
            'view appointments',
            'create appointments',
            'view diagnoses',
            'view treatments',
        ]);
    }
}
