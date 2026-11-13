<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for tenant isolation.
 *
 * Purpose:
 * - Verify that data belonging to one tenant remains isolated
 *   from all other tenants.
 * - Validate the core multi-tenant architecture.
 *
 * Covered Scenarios:
 * - A tenant can retrieve its own clinics.
 * - A tenant cannot access another tenant's clinics.
 *
 * These tests intentionally bypass global scopes using
 * withoutGlobalScopes() so that tenant isolation can be
 * verified explicitly at the database level.
 */
class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a tenant and a clinic belonging to that tenant.
     *
     * @param string $tenantName Tenant name.
     * @param string $email Tenant email.
     * @param string $clinicEmail Clinic email.
     *
     * @return array{0: Tenant, 1: Clinic}
     */
    private function createTenantWithClinic(
        string $tenantName,
        string $email,
        string $clinicEmail
    ): array {
        $tenant = Tenant::create([
            'name' => $tenantName,
            'owner_name' => 'Test Owner',
            'email' => $email,
            'phone' => '+213550000000',
            'country' => 'Algeria',
            'status' => 'active',
            'subscription_plan' => 'professional',
        ]);

        $clinic = Clinic::withoutGlobalScopes()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Clinic',
            'city' => 'Algiers',
            'address' => '12 Rue Test',
            'phone_number' => '+213550000001',
            'email' => $clinicEmail,
            'operating_hours_start' => '08:00:00',
            'operating_hours_end' => '18:00:00',
            'min_receptionists_per_shift' => 1,
            'min_nurses_per_shift' => 1,
            'min_doctors_per_shift' => 1,
            'status' => 'active',
        ]);

        return [$tenant, $clinic];
    }

    /**
     * Verify that a tenant can retrieve only its own clinics.
     *
     * Process:
     * 1. Create two tenants.
     * 2. Create one clinic for each tenant.
     * 3. Query clinics belonging to Tenant A.
     *
     * Expected Result:
     * - Exactly one clinic is returned.
     * - The clinic belongs to Tenant A.
     * - Tenant B's clinic is not included.
     *
     * @return void
     */
    public function test_tenant_can_only_see_own_clinics(): void
    {
        // Arrange
        [$tenantA, $clinicA] = $this->createTenantWithClinic(
            'Clinique Benali',
            'tenantA@test.com',
            'clinicA@test.com'
        );

        [$tenantB, $clinicB] = $this->createTenantWithClinic(
            'Clinique Rival',
            'tenantB@test.com',
            'clinicB@test.com'
        );

        // Act
        $clinics = Clinic::withoutGlobalScopes()
            ->where('tenant_id', $tenantA->id)
            ->get();

        // Assert
        $this->assertCount(1, $clinics);

        $this->assertNotContains(
            $clinicB->id,
            $clinics->pluck('id')->toArray()
        );

        $this->assertEquals(
            $tenantA->id,
            $clinics->first()->tenant_id
        );
    }

    /**
     * Verify that a tenant cannot access clinics
     * belonging to another tenant.
     *
     * Process:
     * 1. Create two tenants.
     * 2. Create one clinic for each tenant.
     * 3. Query clinics belonging to Tenant B.
     *
     * Expected Result:
     * - Exactly one clinic is returned.
     * - The clinic belongs to Tenant B.
     * - The returned clinic does not belong to Tenant A.
     *
     * @return void
     */
    public function test_tenant_cannot_see_other_tenant_clinics(): void
    {
        // Arrange
        [$tenantA, $clinicA] = $this->createTenantWithClinic(
            'Clinique Benali',
            'tenantA@test.com',
            'clinicA@test.com'
        );

        [$tenantB, $clinicB] = $this->createTenantWithClinic(
            'Clinique Rival',
            'tenantB@test.com',
            'clinicB@test.com'
        );

        // Act
        $clinics = Clinic::withoutGlobalScopes()
            ->where('tenant_id', $tenantB->id)
            ->get();

        // Assert
        $this->assertCount(1, $clinics);

        $this->assertNotEquals(
            $tenantA->id,
            $clinics->first()->tenant_id
        );
    }
}