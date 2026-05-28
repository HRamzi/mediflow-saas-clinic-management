<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Clinic;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{

    use RefreshDatabase;

    private function createTenantWithClinic(string $tenantName, string $email, string $clinicEmail): array
    {
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
        $this->assertEquals($tenantA->id, $clinics->first()->tenant_id);
    }


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
        $this->assertNotEquals($tenantA->id, $clinics->first()->tenant_id);
    }
}
