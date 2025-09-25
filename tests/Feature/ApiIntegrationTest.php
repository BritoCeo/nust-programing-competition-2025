<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Diagnosis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class ApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    /**
     * Test AI diagnosis API integration
     */
    public function test_ai_diagnosis_api_integration()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $this->actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $symptoms = Symptom::limit(5)->pluck('id')->toArray();

        $diagnosisData = [
            'symptoms' => $symptoms,
            'patient_id' => $patient->id,
            'additional_notes' => 'Patient shows signs of malaria',
            'patient_age' => 30,
            'patient_gender' => 'male',
            'patient_weight' => 70,
            'patient_height' => 175
        ];

        $response = $this->postJson('/expert-system/analyze', $diagnosisData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'primary_diagnosis' => [
                    'disease_name',
                    'confidence_score',
                    'probability'
                ],
                'alternative_diagnoses',
                'treatment_plan',
                'recommendations'
            ],
            'symptoms_analyzed',
            'analysis_timestamp'
        ]);

        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('primary_diagnosis', $responseData['data']);
        $this->assertArrayHasKey('treatment_plan', $responseData['data']);
    }

    /**
     * Test symptoms API endpoint
     */
    public function test_symptoms_api_endpoint()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $response = $this->getJson('/expert-system/symptoms');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'category',
                    'severity_levels'
                ]
            ]
        ]);
    }

    /**
     * Test diseases API endpoint
     */
    public function test_diseases_api_endpoint()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $response = $this->getJson('/expert-system/diseases');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'symptoms',
                    'treatment_options'
                ]
            ]
        ]);
    }

    /**
     * Test expert system statistics API
     */
    public function test_expert_system_statistics_api()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $this->actingAs($doctor);

        // Create some test diagnoses
        Diagnosis::factory()->count(10)->create(['doctor_id' => $doctor->id]);

        $response = $this->getJson('/expert-system/statistics');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'total_analyses',
                'confirmed_diagnoses',
                'tentative_diagnoses',
                'rejected_diagnoses',
                'average_confidence',
                'disease_distribution'
            ]
        ]);
    }

    /**
     * Test patient diagnoses API
     */
    public function test_patient_diagnoses_api()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $this->actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        // Create some diagnoses for the patient
        Diagnosis::factory()->count(5)->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id
        ]);

        $response = $this->getJson("/expert-system/patient-diagnoses/{$patient->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'diagnosis',
                    'confidence_score',
                    'status',
                    'created_at'
                ]
            ]
        ]);
    }

    /**
     * Test diagnosis status update API
     */
    public function test_diagnosis_status_update_api()
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $this->actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $diagnosis = Diagnosis::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'status' => 'tentative'
        ]);

        $updateData = [
            'status' => 'confirmed',
            'notes' => 'Diagnosis confirmed after additional tests'
        ];

        $response = $this->putJson("/expert-system/diagnosis/{$diagnosis->id}/status", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Diagnosis status updated successfully'
        ]);

        // Verify the diagnosis was updated
        $diagnosis->refresh();
        $this->assertEquals('confirmed', $diagnosis->status);
    }

    /**
     * Test API error handling
     */
    public function test_api_error_handling()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        // Test with invalid symptoms
        $response = $this->postJson('/expert-system/analyze', [
            'symptoms' => [999999], // Non-existent symptom ID
            'patient_id' => 1
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['symptoms.0']);
    }

    /**
     * Test API authentication
     */
    public function test_api_authentication()
    {
        // Test without authentication
        $response = $this->getJson('/expert-system/symptoms');
        $response->assertStatus(401);

        // Test with authentication
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $response = $this->getJson('/expert-system/symptoms');
        $response->assertStatus(200);
    }

    /**
     * Test API rate limiting
     */
    public function test_api_rate_limiting()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        // Make multiple requests quickly
        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson('/expert-system/symptoms');
            $response->assertStatus(200);
        }
    }

    /**
     * Test API response time
     */
    public function test_api_response_time()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $startTime = microtime(true);

        $response = $this->getJson('/expert-system/symptoms');

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(1.0, $executionTime, 'API response should be within 1 second');
    }

    /**
     * Test API data consistency
     */
    public function test_api_data_consistency()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        // Get symptoms data
        $response1 = $this->getJson('/expert-system/symptoms');
        $response2 = $this->getJson('/expert-system/symptoms');

        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $data1 = $response1->json();
        $data2 = $response2->json();

        $this->assertEquals($data1, $data2, 'API responses should be consistent');
    }

    /**
     * Create test data
     */
    private function createTestData()
    {
        // Create symptoms
        Symptom::factory()->count(50)->create();
        
        // Create diseases
        Disease::factory()->count(20)->create();
        
        // Create users with roles
        $roles = ['admin', 'doctor', 'nurse', 'pharmacist', 'receptionist', 'patient'];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
