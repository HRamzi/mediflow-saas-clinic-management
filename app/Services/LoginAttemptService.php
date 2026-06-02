<?php

namespace App\Services;

use App\Models\LoginAttempt;

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