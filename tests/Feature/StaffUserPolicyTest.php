<?php

namespace Tests\Feature;

use App\Models\StaffUser;
use Database\Seeders\ClinicSeeder;
use Database\Seeders\StaffUserSeeder;
use Database\Seeders\TenantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for StaffUserPolicy authorization rules.
 *
 * Purpose:
 * - Verify that users with the appropriate role can access
 *   the staff user listing endpoint.
 * - Verify that unauthorized roles are denied access.
 *
 * Covered Scenarios:
 * - Owner can view the staff user list.
 * - Receptionist cannot view the staff user list.
 *
 * Test Endpoint:
 * - GET /api/v1/staff/users
 */
class StaffUserPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed the database with tenants, clinics, and staff users
     * before each test.
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
     * Verify that an owner can successfully retrieve
     * the staff user list.
     *
     * Expected Result:
     * - HTTP 200 OK
     * - success = true
     * - response contains staff user data
     *
     * @return void
     */
    public function test_owner_can_view_staff_list(): void
    {
        $user = StaffUser::withoutGlobalScopes()
            ->where('email', 'karim@cliniquebenali.dz')
            ->first();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/staff/users');

        // Assert successful response.
        $response->assertStatus(200);

        $response->assertJsonPath('success', true);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'email',
                    'role',
                ],
            ],
        ]);
    }

    /**
     * Verify that a receptionist is forbidden from
     * accessing the staff user list.
     *
     * Expected Result:
     * - HTTP 403 Forbidden
     * - success = false
     * - data = null
     *
     * @return void
     */
    public function test_receptionist_cannot_view_staff_list(): void
    {
        $user = StaffUser::withoutGlobalScopes()
            ->where('role', 'receptionist')
            ->first();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/staff/users');

        // Assert forbidden response.
        $response->assertStatus(403);

        $response->assertJsonPath('success', false);

        $response->assertJsonPath('data', null);
    }
}