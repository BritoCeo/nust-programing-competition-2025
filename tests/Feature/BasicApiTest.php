<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class BasicApiTest extends TestCase
{
    /** @test */
    public function health_check_endpoint_works()
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'version',
            'services'
        ]);
    }

    /** @test */
    public function user_can_login_via_api()
    {
        $user = $this->createDoctor([
            'email' => 'doctor@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'doctor@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertNotEmpty($response->json('data.token'));
    }

    /** @test */
    public function protected_routes_require_authentication()
    {
        $response = $this->getJson('/api/dashboard/stats');
        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $doctor = $this->createDoctor();
        Sanctum::actingAs($doctor);

        $response = $this->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
