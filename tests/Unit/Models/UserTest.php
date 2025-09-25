<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Treatment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles and permissions
        $this->createRolesAndPermissions();
    }

    private function createRolesAndPermissions()
    {
        $roles = ['admin', 'doctor', 'nurse', 'pharmacist', 'receptionist', 'patient'];
        $permissions = [
            'view medical records', 'create medical records', 'edit medical records',
            'view appointments', 'create appointments', 'edit appointments',
            'use expert system', 'view reports'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'Dr. John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'phone' => '+264811234567',
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'specialization' => 'Internal Medicine',
            'is_active' => true
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Dr. John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue($user->is_active);
    }

    /** @test */
    public function it_can_assign_roles_to_user()
    {
        $user = User::factory()->create();
        $doctorRole = Role::where('name', 'doctor')->first();
        
        $user->assignRole('doctor');
        
        $this->assertTrue($user->hasRole('doctor'));
        $this->assertEquals('doctor', $user->role_name);
    }

    /** @test */
    public function it_can_check_if_user_is_medical_professional()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        
        $nurse = User::factory()->create();
        $nurse->assignRole('nurse');
        
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $this->assertTrue($doctor->isMedicalProfessional());
        $this->assertTrue($nurse->isMedicalProfessional());
        $this->assertFalse($patient->isMedicalProfessional());
    }

    /** @test */
    public function it_can_check_if_user_is_patient()
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $this->assertTrue($patient->isPatient());
        $this->assertFalse($doctor->isPatient());
    }

    /** @test */
    public function it_can_get_role_name_attribute()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        
        $this->assertEquals('doctor', $user->role_name);
    }

    /** @test */
    public function it_can_have_medical_records()
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'record_number' => 'MR20250115001',
            'visit_date' => now(),
            'chief_complaint' => 'Fever and headache',
            'status' => 'active'
        ]);

        $this->assertCount(1, $patient->medicalRecords);
        $this->assertInstanceOf(MedicalRecord::class, $patient->medicalRecords->first());
    }

    /** @test */
    public function it_can_have_appointments()
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_number' => 'APT20250115001',
            'appointment_date' => now()->addDays(1),
            'appointment_time' => now()->addDays(1),
            'type' => 'consultation',
            'reason' => 'Follow-up visit',
            'status' => 'scheduled',
            'created_by' => $doctor->id
        ]);

        $this->assertCount(1, $patient->appointments);
        $this->assertInstanceOf(Appointment::class, $patient->appointments->first());
    }

    /** @test */
    public function it_can_have_patients_relationship()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'record_number' => 'MR20250115001',
            'visit_date' => now(),
            'chief_complaint' => 'Fever and headache',
            'status' => 'active'
        ]);

        $this->assertCount(1, $doctor->patients);
        $this->assertEquals($patient->id, $doctor->patients->first()->id);
    }

    /** @test */
    public function it_can_cast_attributes_correctly()
    {
        $user = User::factory()->create([
            'date_of_birth' => '1980-01-01',
            'is_active' => true
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->date_of_birth);
        $this->assertTrue($user->is_active);
    }

    /** @test */
    public function it_can_hide_sensitive_attributes()
    {
        $user = User::factory()->create();
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /** @test */
    public function it_can_validate_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::create([
            'email' => 'test@example.com'
            // Missing required 'name' field
        ]);
    }

    /** @test */
    public function it_can_validate_unique_email()
    {
        User::factory()->create(['email' => 'test@example.com']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com', // Duplicate email
            'password' => 'password123'
        ]);
    }
}
