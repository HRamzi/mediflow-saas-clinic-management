<?php

namespace Tests\Feature;

use App\Models\StaffUser;
use Database\Seeders\ClinicSeeder;
use Database\Seeders\StaffUserSeeder;
use Database\Seeders\TenantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for account lockout and unlock functionality.
 *
 * Purpose:
 * - Verify accounts are automatically locked after repeated
 *   failed login attempts.
 * - Verify owners can unlock locked accounts.
 * - Verify non-authorized staff members cannot unlock accounts.
 *
 * Covered Scenarios:
 * - Three failed login attempts lock an account.
 * - Owner can unlock a locked account.
 * - Receptionist cannot unlock a locked account.
 */
class AccountLockoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed required data before each test.
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
     * Verify that an account is automatically locked
     * after three failed login attempts.
     *
     * Process:
     * 1. Submit three failed login attempts.
     * 2. Attempt login with the correct password.
     * 3. Verify access is denied.
     *
     * Expected Result:
     * - Account becomes inactive.
     * - Login returns HTTP 401.
     *
     * @return void
     */
    public function test_account_locked_after_three_failed_attempts(): void
    {
        // Arrange & Act — three failed login attempts.
        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'ahmed.meziane@cliniquebenali.dz',
                'password' => 'wrongpassword',
            ]);
        }

        // Act — attempt login with correct credentials.
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'ahmed.meziane@cliniquebenali.dz',
            'password' => 'Doctor1234',
        ]);

        // Assert.
        $response->assertStatus(401);

        $user = StaffUser::withoutGlobalScopes()
            ->where('email', 'ahmed.meziane@cliniquebenali.dz')
            ->first();

        $this->assertFalse($user->is_active);
    }

    /**
     * Verify that an owner can unlock a locked account.
     *
     * Process:
     * 1. Lock a user account.
     * 2. Authenticate as an owner.
     * 3. Call the unlock endpoint.
     * 4. Verify the account becomes active.
     *
     * Expected Result:
     * - HTTP 200 OK.
     * - User account is active.
     *
     * @return void
     */
    public function test_owner_can_unlock_account(): void
    {
        // Arrange
        $owner = StaffUser::withoutGlobalScopes()
            ->where('role', 'owner')
            ->first();

        $lockedUser = StaffUser::withoutGlobalScopes()
            ->where('email', 'ahmed.meziane@cliniquebenali.dz')
            ->first();

        $lockedUser->update([
            'is_active' => false,
        ]);

        // Act
        $response = $this->actingAs($owner, 'sanctum')
            ->putJson("/api/v1/staff/users/{$lockedUser->id}/unlock");

        // Assert
        $response->assertStatus(200);

        $lockedUser->refresh();

        $this->assertTrue($lockedUser->is_active);
    }

    /**
     * Verify that a receptionist cannot unlock accounts.
     *
     * Process:
     * 1. Authenticate as a receptionist.
     * 2. Attempt to unlock a staff user.
     *
     * Expected Result:
     * - HTTP 403 Forbidden.
     * - Account remains locked.
     *
     * @return void
     */
    public function test_receptionist_cannot_unlock_account(): void
    {
        // Arrange
        $receptionist = StaffUser::withoutGlobalScopes()
            ->where('role', 'receptionist')
            ->first();

        $lockedUser = StaffUser::withoutGlobalScopes()
            ->where('email', 'ahmed.meziane@cliniquebenali.dz')
            ->first();

        $lockedUser->update([
            'is_active' => false,
        ]);

        // Act
        $response = $this->actingAs($receptionist, 'sanctum')
            ->putJson("/api/v1/staff/users/{$lockedUser->id}/unlock");

        // Assert
        $response->assertStatus(403);

        $lockedUser->refresh();

        $this->assertFalse($lockedUser->is_active);
    }
}
