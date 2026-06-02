<?php

namespace Tests\Feature;

use Database\Seeders\ClinicSeeder;
use Database\Seeders\StaffUserSeeder;
use Database\Seeders\TenantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for authentication endpoints.
 *
 * Purpose:
 * - Verify staff users can authenticate successfully.
 * - Verify invalid credentials are rejected.
 * - Verify authenticated users can access protected endpoints.
 *
 * Covered Endpoints:
 * - POST /api/v1/auth/login
 * - GET /api/v1/auth/me
 *
 * Covered Scenarios:
 * - Successful login.
 * - Failed login due to invalid credentials.
 * - Accessing the authenticated user endpoint.
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed the database before each test.
     *
     * Seeders:
     * - TenantSeeder
     * - ClinicSeeder
     * - StaffUserSeeder
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            TenantSeeder::class,
            ClinicSeeder::class,
            StaffUserSeeder::class,
        ]);
    }

    /**
     * Verify that a staff user can log in with valid credentials.
     *
     * Expected Result:
     * - HTTP 200 OK
     * - success = true
     * - response contains an access token
     * - response contains authenticated user data
     *
     * @return void
     */
    public function test_staff_can_login_with_valid_credentials(): void
    {
        // Act — send login request.
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'karim@cliniquebenali.dz',
            'password' => 'Admin1234',
        ]);

        // Assert successful response.
        $response->assertStatus(200);

        $response->assertJsonPath('success', true);

        $response->assertJsonStructure([
            'data' => [
                'token',
                'user' => [
                    'id',
                    'email',
                    'role',
                ],
            ],
        ]);
    }

    /**
     * Verify that login fails when invalid credentials are provided.
     *
     * Expected Result:
     * - HTTP 401 Unauthorized
     * - success = false
     * - data = null
     *
     * @return void
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        // Act — send login request with invalid password.
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'karim@cliniquebenali.dz',
            'password' => 'Admin12345',
        ]);

        // Assert unauthorized response.
        $response->assertStatus(401);

        $response->assertJsonPath('success', false);

        $response->assertJsonPath('data', null);
    }

    /**
     * Verify that an authenticated user can access
     * the current user endpoint.
     *
     * Process:
     * 1. Authenticate with valid credentials.
     * 2. Extract the access token.
     * 3. Call the protected /me endpoint.
     *
     * Expected Result:
     * - HTTP 200 OK
     *
     * @return void
     */
    public function test_authenticated_user_can_access_me_endpoint(): void
    {
        // Arrange — authenticate and obtain token.
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'karim@cliniquebenali.dz',
            'password' => 'Admin1234',
        ]);

        $token = $loginResponse->json('data.token');

        // Act — call protected endpoint.
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/auth/me');

        // Assert successful response.
        $response->assertStatus(200);
    }
}