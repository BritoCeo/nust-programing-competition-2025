<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Treatment;
use App\Models\DrugAdministration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WebIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test welcome page loads correctly
     */
    public function test_welcome_page_loads()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    /**
     * Test login page loads correctly
     */
    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test register page loads correctly
     */
    public function test_register_page_loads()
    {
        $response = $this->get('/register');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test dashboard redirects when not authenticated
     */
    public function test_dashboard_redirects_when_not_authenticated()
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test dashboard loads for authenticated user
     */
    public function test_dashboard_loads_for_authenticated_user()
    {
        $user = User::factory()->create();
        $user->assignRole('patient');
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /**
     * Test medical records page loads for authenticated user
     */
    public function test_medical_records_page_loads_for_authenticated_user()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        
        $response = $this->actingAs($user)->get('/medical-records');
        
        $response->assertStatus(200);
        $response->assertViewIs('medical-records.index');
    }

    /**
     * Test appointments page loads for authenticated user
     */
    public function test_appointments_page_loads_for_authenticated_user()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        
        $response = $this->actingAs($user)->get('/appointments');
        
        $response->assertStatus(200);
        $response->assertViewIs('appointments.index');
    }

    /**
     * Test expert system page loads for authenticated user
     */
    public function test_expert_system_page_loads_for_authenticated_user()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        
        $response = $this->actingAs($user)->get('/expert-system');
        
        $response->assertStatus(200);
        $response->assertViewIs('expert-system.index');
    }

    /**
     * Test pharmacy page loads for authenticated user
     */
    public function test_pharmacy_page_loads_for_authenticated_user()
    {
        $user = User::factory()->create();
        $user->assignRole('pharmacist');
        
        $response = $this->actingAs($user)->get('/pharmacy');
        
        $response->assertStatus(200);
        $response->assertViewIs('pharmacy.index');
    }

    /**
     * Test reports page loads for authenticated user
     */
    public function test_reports_page_loads_for_authenticated_user()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        
        $response = $this->actingAs($user)->get('/reports');
        
        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
    }

    /**
     * Test user registration works
     */
    public function test_user_registration_works()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient'
        ];

        $response = $this->post('/register', $userData);
        
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    /**
     * Test user login works
     */
    public function test_user_login_works()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);
        $user->assignRole('patient');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);
        
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Test dashboard shows correct data for different roles
     */
    public function test_dashboard_shows_correct_data_for_doctor()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        
        // Create some test data
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id
        ]);
        
        $medicalRecord = MedicalRecord::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id
        ]);

        $response = $this->actingAs($doctor)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        
        // Check that the dashboard data contains the expected statistics
        $viewData = $response->viewData('data');
        $this->assertArrayHasKey('stats', $viewData);
        $this->assertArrayHasKey('modules', $viewData);
    }

    /**
     * Test logout works
     */
    public function test_logout_works()
    {
        $user = User::factory()->create();
        $user->assignRole('patient');
        
        $this->actingAs($user);
        
        $response = $this->post('/logout');
        
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
