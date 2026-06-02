<?php

namespace App\Services;

use App\Models\LoginAttempt;
use App\Models\StaffUser;

/**
 * Service responsible for recording login attempts
 * and enforcing account lockout rules.
 */
class LoginAttemptService
{
    /**
     * Log a successful login attempt.
     *
     * @param array $data
     * @return void
     */
    public function logSuccess(array $data): void
    {
        $this->log($data, true);
    }

    /**
     * Log a failed login attempt.
     *
     * If the user exists and reaches three failed
     * login attempts, the account is automatically
     * deactivated.
     *
     * @param array $data
     * @return void
     */
    public function logFailure(array $data): void
    {
        $this->log(
            $data,
            false,
            $data['failure_reason'] ?? null
        );

        if (empty($data['matched_user_id'])) {
            return;
        }

        $failureCount = LoginAttempt::query()
            ->where('matched_user_id', $data['matched_user_id'])
            ->where('success', false)
            ->count();

        if ($failureCount >= 3) {
            StaffUser::withoutGlobalScopes()
                ->where('id', $data['matched_user_id'])
                ->update([
                    'is_active' => false,
                ]);
        }
    }

    /**
     * Create a login attempt record.
     *
     * @param array $data
     * @param bool $success
     * @param string|null $failureReason
     * @return void
     */
    private function log(
        array $data,
        bool $success,
        ?string $failureReason = null
    ): void {
        LoginAttempt::create([
            'attempted_email' => $data['attempted_email'],
            'matched_user_id' => $data['matched_user_id'] ?? null,
            'ip_address'      => $data['ip_address'] ?? null,
            'device_info'     => $data['device_info'] ?? null,
            'success'         => $success,
            'failure_reason'  => $failureReason,
            'attempted_at'    => now(),
        ]);
    }
}
