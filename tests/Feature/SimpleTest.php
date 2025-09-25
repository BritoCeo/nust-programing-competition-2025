<?php

namespace Tests\Feature;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    /** @test */
    public function basic_test_passes()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function health_check_works()
    {
        $response = $this->get('/api/health');
        $response->assertStatus(200);
    }
}
