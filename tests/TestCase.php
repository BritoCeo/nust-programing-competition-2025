<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles and permissions for testing
        $this->createRolesAndPermissions();
    }

    protected function createRolesAndPermissions()
    {
        $roles = [
            'admin' => [
                'view medical records', 'create medical records', 'edit medical records', 'delete medical records',
                'view appointments', 'create appointments', 'edit appointments', 'cancel appointments',
                'view diagnoses', 'create diagnoses', 'edit diagnoses', 'delete diagnoses',
                'view treatments', 'create treatments', 'edit treatments', 'delete treatments',
                'view pharmacy', 'manage pharmacy', 'dispense drugs', 'view drug inventory',
                'view reports', 'generate reports', 'export reports',
                'view users', 'create users', 'edit users', 'delete users', 'assign roles',
                'use expert system', 'view expert system', 'manage expert system'
            ],
            'doctor' => [
                'view medical records', 'create medical records', 'edit medical records',
                'view appointments', 'create appointments', 'edit appointments',
                'view diagnoses', 'create diagnoses', 'edit diagnoses',
                'view treatments', 'create treatments', 'edit treatments',
                'use expert system', 'view expert system',
                'view reports', 'generate reports'
            ],
            'nurse' => [
                'view medical records', 'create medical records', 'edit medical records',
                'view appointments', 'create appointments', 'edit appointments',
                'view diagnoses', 'view treatments', 'create treatments', 'edit treatments',
                'view reports'
            ],
            'pharmacist' => [
                'view pharmacy', 'manage pharmacy', 'dispense drugs', 'view drug inventory',
                'view treatments', 'view reports'
            ],
            'receptionist' => [
                'view appointments', 'create appointments', 'edit appointments', 'cancel appointments',
                'view users', 'create users', 'edit users',
                'view reports'
            ],
            'patient' => [
                'view medical records',
                'view appointments', 'create appointments',
                'view diagnoses',
                'view treatments'
            ]
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                $role->givePermissionTo($permission);
            }
        }
    }

    protected function createUserWithRole(string $role, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($role);
        return $user;
    }

    protected function createAdmin(array $attributes = []): User
    {
        return $this->createUserWithRole('admin', $attributes);
    }

    protected function createDoctor(array $attributes = []): User
    {
        return $this->createUserWithRole('doctor', $attributes);
    }

    protected function createNurse(array $attributes = []): User
    {
        return $this->createUserWithRole('nurse', $attributes);
    }

    protected function createPharmacist(array $attributes = []): User
    {
        return $this->createUserWithRole('pharmacist', $attributes);
    }

    protected function createReceptionist(array $attributes = []): User
    {
        return $this->createUserWithRole('receptionist', $attributes);
    }

    protected function createPatient(array $attributes = []): User
    {
        return $this->createUserWithRole('patient', $attributes);
    }
}