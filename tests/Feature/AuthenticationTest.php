<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\TenantSeeder;
use Database\Seeders\ClinicSeeder;
use Database\Seeders\StaffUserSeeder;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            TenantSeeder::class,
            ClinicSeeder::class,
            StaffUserSeeder::class,
        ]);
    }

    public function test_staff_can_login_with_valid_credentials(): void
    {

        // ACT — sending the HTTP request IS the action
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'karim@cliniquebenali.dz',
            'password' => 'Admin1234',
        ]);

        // ASSERT — checking the response
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'data' => [
                'token',
                'user' => [
                    'id',
                    'email',
                    'role',
                ]
            ]
        ]);
    }


    public function test_login_fails_with_invalid_credentials(): void
    {

        // ACT — sending the HTTP request IS the action
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'karim@cliniquebenali.dz',
            'password' => 'Admin12345',
        ]);

        // ASSERT — checking the response
        $response->assertStatus(401);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('data', null);
    }

    public function test_authenticated_user_can_access_me_endpoint(): void
    {

        // Arrange — login to get a token
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'karim@cliniquebenali.dz',
            'password' => 'Admin1234',
        ]);

        $token = $loginResponse->json('data.token');

        // Act — call the me endpoint with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/auth/me');

        // Assert
        $response->assertStatus(200);
    }
}
