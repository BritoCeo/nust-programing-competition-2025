<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users for different roles
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Dr. Admin',
            'email' => 'admin@mesmtf.com',
            'password' => Hash::make('password123'),
            'phone' => '+264811234567',
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'specialization' => 'Administration',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create Doctor User
        $doctor = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@mesmtf.com',
            'password' => Hash::make('password123'),
            'phone' => '+264811234568',
            'date_of_birth' => '1985-05-15',
            'gender' => 'male',
            'specialization' => 'Internal Medicine',
            'medical_license_number' => 'MD123456',
            'is_active' => true,
        ]);
        $doctor->assignRole('doctor');

        // Create Nurse User
        $nurse = User::create([
            'name' => 'Nurse Jane Doe',
            'email' => 'nurse@mesmtf.com',
            'password' => Hash::make('password123'),
            'phone' => '+264811234569',
            'date_of_birth' => '1990-03-20',
            'gender' => 'female',
            'specialization' => 'General Nursing',
            'is_active' => true,
        ]);
        $nurse->assignRole('nurse');

        // Create Patient User
        $patient = User::create([
            'name' => 'Patient Alice Johnson',
            'email' => 'patient@mesmtf.com',
            'password' => Hash::make('password123'),
            'phone' => '+264811234570',
            'date_of_birth' => '1995-08-10',
            'gender' => 'female',
            'is_active' => true,
        ]);
        $patient->assignRole('patient');

        // Create Pharmacist User
        $pharmacist = User::create([
            'name' => 'Pharmacist Bob Wilson',
            'email' => 'pharmacist@mesmtf.com',
            'password' => Hash::make('password123'),
            'phone' => '+264811234571',
            'date_of_birth' => '1988-12-05',
            'gender' => 'male',
            'specialization' => 'Clinical Pharmacy',
            'is_active' => true,
        ]);
        $pharmacist->assignRole('pharmacist');

        // Create Receptionist User
        $receptionist = User::create([
            'name' => 'Receptionist Carol Brown',
            'email' => 'receptionist@mesmtf.com',
            'password' => Hash::make('password123'),
            'phone' => '+264811234572',
            'date_of_birth' => '1992-07-15',
            'gender' => 'female',
            'is_active' => true,
        ]);
        $receptionist->assignRole('receptionist');

        $this->command->info('Test users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@mesmtf.com / password123');
        $this->command->info('Doctor: doctor@mesmtf.com / password123');
        $this->command->info('Nurse: nurse@mesmtf.com / password123');
        $this->command->info('Patient: patient@mesmtf.com / password123');
        $this->command->info('Pharmacist: pharmacist@mesmtf.com / password123');
        $this->command->info('Receptionist: receptionist@mesmtf.com / password123');
    }
}