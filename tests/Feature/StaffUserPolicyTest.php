<?php

namespace Tests\Feature;

use App\Models\StaffUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\TenantSeeder;
use Database\Seeders\ClinicSeeder;
use Database\Seeders\StaffUserSeeder;

class StaffUserPolicyTest extends TestCase
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

    public function test_owner_can_view_staff_list(): void
    {
        $user = StaffUser::withoutGlobalScopes()
            ->where('email', 'karim@cliniquebenali.dz')
            ->first();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/staff/users');

        // ASSERT — checking the response
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'email',
                    'role',
                ]
            ]
        ]);
    }

    public function test_receptionist_cannot_view_staff_list(): void
    {
        $user = StaffUser::withoutGlobalScopes()
            ->where('role', 'receptionist')
            ->first();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/staff/users');

        // ASSERT — checking the response
        $response->assertStatus(403);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('data', null);
    }
}
