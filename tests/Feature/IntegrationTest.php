<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IntegrationTest extends TestCase
{
    /**
     * Test that the application loads without errors
     */
    public function test_application_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test that the dashboard route exists
     */
    public function test_dashboard_route_exists()
    {
        $response = $this->get('/dashboard');
        // Should redirect to login since not authenticated
        $response->assertStatus(302);
    }

    /**
     * Test that medical records route exists
     */
    public function test_medical_records_route_exists()
    {
        $response = $this->get('/medical-records');
        // Should redirect to login since not authenticated
        $response->assertStatus(302);
    }

    /**
     * Test that appointments route exists
     */
    public function test_appointments_route_exists()
    {
        $response = $this->get('/appointments');
        // Should redirect to login since not authenticated
        $response->assertStatus(302);
    }

    /**
     * Test that expert system route exists
     */
    public function test_expert_system_route_exists()
    {
        $response = $this->get('/expert-system');
        // Should redirect to login since not authenticated
        $response->assertStatus(302);
    }

    /**
     * Test that pharmacy route exists
     */
    public function test_pharmacy_route_exists()
    {
        $response = $this->get('/pharmacy');
        // Should redirect to login since not authenticated
        $response->assertStatus(302);
    }

    /**
     * Test that reports route exists
     */
    public function test_reports_route_exists()
    {
        $response = $this->get('/reports');
        // Should redirect to login since not authenticated
        $response->assertStatus(302);
    }

    /**
     * Test that login route exists
     */
    public function test_login_route_exists()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test that register route exists
     */
    public function test_register_route_exists()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test that all main routes are accessible
     */
    public function test_all_main_routes_accessible()
    {
        $routes = [
            '/' => 200,
            '/login' => 200,
            '/register' => 200,
            '/dashboard' => 302, // Redirects to login
            '/medical-records' => 302, // Redirects to login
            '/appointments' => 302, // Redirects to login
            '/expert-system' => 302, // Redirects to login
            '/pharmacy' => 302, // Redirects to login
            '/reports' => 302, // Redirects to login
        ];

        foreach ($routes as $route => $expectedStatus) {
            $response = $this->get($route);
            $response->assertStatus($expectedStatus, "Route {$route} should return status {$expectedStatus}");
        }
    }

    /**
     * Test that the application has proper error handling
     */
    public function test_application_error_handling()
    {
        // Test 404 for non-existent route
        $response = $this->get('/non-existent-route');
        $response->assertStatus(404);
    }

    /**
     * Test that the application returns proper content type
     */
    public function test_application_content_type()
    {
        $response = $this->get('/');
        $response->assertHeader('content-type', 'text/html; charset=UTF-8');
    }

    /**
     * Test that the application has proper security headers
     */
    public function test_application_security_headers()
    {
        $response = $this->get('/');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }
}
