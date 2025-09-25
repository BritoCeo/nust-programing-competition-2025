<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Diagnosis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database query performance
     */
    public function test_database_query_performance()
    {
        // Create test data
        $this->createLargeDataset();

        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        // Test dashboard query performance
        $startTime = microtime(true);
        
        $response = $this->get('/dashboard');
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(2.0, $executionTime, 'Dashboard should load within 2 seconds');
    }

    /**
     * Test medical records pagination performance
     */
    public function test_medical_records_pagination_performance()
    {
        $this->createLargeDataset();

        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $startTime = microtime(true);
        
        $response = $this->get('/medical-records');
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(1.5, $executionTime, 'Medical records should load within 1.5 seconds');
    }

    /**
     * Test appointment search performance
     */
    public function test_appointment_search_performance()
    {
        $this->createLargeDataset();

        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $startTime = microtime(true);
        
        $response = $this->get('/appointments?search=test');
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(1.0, $executionTime, 'Appointment search should complete within 1 second');
    }

    /**
     * Test concurrent user access
     */
    public function test_concurrent_user_access()
    {
        $this->createLargeDataset();

        // Simulate multiple users accessing the system
        $users = User::factory()->count(10)->create();
        foreach ($users as $user) {
            $user->assignRole('doctor');
        }

        $responses = [];
        $startTime = microtime(true);

        // Simulate concurrent requests
        foreach ($users as $user) {
            $this->actingAs($user);
            $responses[] = $this->get('/dashboard');
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // All responses should be successful
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $this->assertLessThan(5.0, $executionTime, 'Concurrent access should complete within 5 seconds');
    }

    /**
     * Test memory usage
     */
    public function test_memory_usage()
    {
        $initialMemory = memory_get_usage();
        
        $this->createLargeDataset();

        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        $finalMemory = memory_get_usage();
        $memoryUsed = ($finalMemory - $initialMemory) / 1024 / 1024; // MB

        $this->assertLessThan(50, $memoryUsed, 'Memory usage should be less than 50MB');
    }

    /**
     * Test database connection performance
     */
    public function test_database_connection_performance()
    {
        $startTime = microtime(true);
        
        DB::connection()->getPdo();
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->assertLessThan(0.1, $executionTime, 'Database connection should be established within 0.1 seconds');
    }

    /**
     * Test file upload performance
     */
    public function test_file_upload_performance()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $this->actingAs($user);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $startTime = microtime(true);

        $response = $this->post('/medical-records', [
            'patient_id' => $patient->id,
            'record_number' => 'MR-' . time(),
            'chief_complaint' => 'Test complaint',
            'attachments' => [
                \Illuminate\Http\UploadedFile::fake()->create('test.pdf', 1000)
            ]
        ]);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertRedirect('/medical-records');
        $this->assertLessThan(3.0, $executionTime, 'File upload should complete within 3 seconds');
    }

    /**
     * Create large dataset for performance testing
     */
    private function createLargeDataset()
    {
        // Create 1000 medical records
        MedicalRecord::factory()->count(1000)->create();
        
        // Create 500 appointments
        Appointment::factory()->count(500)->create();
        
        // Create 200 diagnoses
        Diagnosis::factory()->count(200)->create();
        
        // Create 100 users
        User::factory()->count(100)->create();
    }
}
