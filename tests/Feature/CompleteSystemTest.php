<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Drug;
use App\Models\DrugAdministration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompleteSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test roles
        $this->createRoles();
        
        // Create test data
        $this->createTestData();
    }

    /**
     * Test complete user journey - Patient registration to diagnosis
     */
    public function test_complete_patient_journey()
    {
        // 1. Patient Registration
        $patientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',
            'phone' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male'
        ];

        $response = $this->post('/register', $patientData);
        $response->assertRedirect('/dashboard');

        // 2. Patient Login
        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        // 3. Check Dashboard Access
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');

        // 4. Check Medical Records Access
        $response = $this->get('/medical-records');
        $response->assertStatus(200);
        $response->assertViewIs('medical-records.index');
    }

    /**
     * Test doctor workflow - From login to diagnosis
     */
    public function test_doctor_workflow()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $this->actingAs($doctor);

        // 1. Check Dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // 2. Create Medical Record
        $medicalRecordData = [
            'patient_id' => $patient->id,
            'record_number' => 'MR-' . time(),
            'chief_complaint' => 'Fever and headache',
            'history_of_present_illness' => 'Patient reports fever for 3 days',
            'physical_examination' => 'Temperature 38.5°C, normal blood pressure',
            'diagnosis' => 'Suspected malaria',
            'treatment_plan' => 'Antimalarial medication',
            'vital_signs' => [
                'temperature' => 38.5,
                'blood_pressure_systolic' => 120,
                'blood_pressure_diastolic' => 80,
                'heart_rate' => 85
            ]
        ];

        $response = $this->post('/medical-records', $medicalRecordData);
        $response->assertRedirect('/medical-records');
        $response->assertSessionHas('success');

        // 3. Check Expert System Access
        $response = $this->get('/expert-system');
        $response->assertStatus(200);
        $response->assertViewIs('expert-system.index');

        // 4. Test AI Diagnosis
        $symptoms = Symptom::limit(3)->pluck('id')->toArray();
        $diagnosisData = [
            'symptoms' => $symptoms,
            'patient_id' => $patient->id,
            'additional_notes' => 'Patient shows signs of malaria',
            'patient_age' => 30,
            'patient_gender' => 'male'
        ];

        $response = $this->post('/expert-system/analyze', $diagnosisData);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /**
     * Test appointment scheduling workflow
     */
    public function test_appointment_scheduling_workflow()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $this->actingAs($patient);

        // 1. Schedule Appointment
        $appointmentData = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDays(1)->format('Y-m-d'),
            'appointment_time' => '10:00',
            'reason' => 'Regular checkup',
            'type' => 'consultation',
            'notes' => 'Patient requested morning appointment',
            'duration' => 30,
            'priority' => 'medium'
        ];

        $response = $this->post('/appointments', $appointmentData);
        $response->assertRedirect('/appointments');
        $response->assertSessionHas('success');

        // 2. Check Appointment List
        $response = $this->get('/appointments');
        $response->assertStatus(200);
        $response->assertViewIs('appointments.index');

        // 3. Test Appointment Status Update
        $appointment = Appointment::where('patient_id', $patient->id)->first();
        $response = $this->post("/appointments/{$appointment->id}/confirm");
        $response->assertRedirect('/appointments');
    }

    /**
     * Test pharmacy workflow
     */
    public function test_pharmacy_workflow()
    {
        $pharmacist = User::factory()->create();
        $pharmacist->assignRole('pharmacist');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $this->actingAs($pharmacist);

        // 1. Check Pharmacy Dashboard
        $response = $this->get('/pharmacy');
        $response->assertStatus(200);
        $response->assertViewIs('pharmacy.index');

        // 2. Test Drug Dispensing
        $drug = Drug::factory()->create();
        $dispenseData = [
            'drug_id' => $drug->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'quantity' => 10,
            'dosage' => '500mg',
            'frequency' => 'Twice daily',
            'duration' => '7 days',
            'instructions' => 'Take with food'
        ];

        $response = $this->post('/pharmacy/dispense', $dispenseData);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /**
     * Test file upload functionality
     */
    public function test_file_upload_functionality()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $this->actingAs($doctor);

        Storage::fake('public');

        // Create test file
        $file = UploadedFile::fake()->create('test-document.pdf', 100);

        $medicalRecordData = [
            'patient_id' => $patient->id,
            'record_number' => 'MR-' . time(),
            'chief_complaint' => 'Test complaint',
            'attachments' => [$file]
        ];

        $response = $this->post('/medical-records', $medicalRecordData);
        $response->assertRedirect('/medical-records');

        // Check if file was stored
        Storage::disk('public')->assertExists('medical-records/1/' . $file->hashName());
    }

    /**
     * Test report generation
     */
    public function test_report_generation()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $this->actingAs($doctor);

        // 1. Check Reports Page
        $response = $this->get('/reports');
        $response->assertStatus(200);
        $response->assertViewIs('reports.index');

        // 2. Generate Report
        $reportData = [
            'report_type' => 'patient_summary',
            'report_name' => 'Test Report',
            'description' => 'Test report generation',
            'period_start' => now()->subMonth()->format('Y-m-d'),
            'period_end' => now()->format('Y-m-d')
        ];

        $response = $this->post('/reports/generate', $reportData);
        $response->assertRedirect('/reports');
        $response->assertSessionHas('success');
    }

    /**
     * Test role-based access control
     */
    public function test_role_based_access_control()
    {
        // Test Patient Access
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $this->actingAs($patient);

        $this->get('/medical-records')->assertStatus(200);
        $this->get('/appointments')->assertStatus(200);
        $this->get('/expert-system')->assertStatus(403); // Patients can't access expert system
        $this->get('/pharmacy')->assertStatus(403); // Patients can't access pharmacy

        // Test Doctor Access
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $this->actingAs($doctor);

        $this->get('/medical-records')->assertStatus(200);
        $this->get('/appointments')->assertStatus(200);
        $this->get('/expert-system')->assertStatus(200);
        $this->get('/pharmacy')->assertStatus(403); // Doctors can't access pharmacy
        $this->get('/reports')->assertStatus(200);

        // Test Pharmacist Access
        $pharmacist = User::factory()->create();
        $pharmacist->assignRole('pharmacist');
        $this->actingAs($pharmacist);

        $this->get('/pharmacy')->assertStatus(200);
        $this->get('/reports')->assertStatus(200);
        $this->get('/expert-system')->assertStatus(403); // Pharmacists can't access expert system
    }

    /**
     * Test API endpoints
     */
    public function test_api_endpoints()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        // Test Dashboard API
        $response = $this->get('/api/dashboard/stats');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Test Medical Records API
        $response = $this->get('/api/medical-records');
        $response->assertStatus(200);

        // Test Appointments API
        $response = $this->get('/api/appointments');
        $response->assertStatus(200);

        // Test Expert System API
        $response = $this->get('/api/expert-system/symptoms');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /**
     * Test mobile responsiveness
     */
    public function test_mobile_responsiveness()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        // Test with mobile user agent
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)'
        ])->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /**
     * Create test roles
     */
    private function createRoles()
    {
        $roles = ['admin', 'doctor', 'nurse', 'pharmacist', 'receptionist', 'patient'];
        
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role, 'guard_name' => 'web']);
        }
    }

    /**
     * Create test data
     */
    private function createTestData()
    {
        // Create symptoms
        Symptom::factory()->count(20)->create();
        
        // Create diseases
        Disease::factory()->count(10)->create();
        
        // Create drugs
        Drug::factory()->count(15)->create();
    }
}
