<?php

namespace App\Services;

use App\Models\StaffUser;
use App\Repositories\AuthRepository;
use App\Services\LoginAttemptService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Hash;

/**
 * Handles authentication-related business logic.
 *
 * Responsibilities:
 * - Authenticate staff users
 * - Generate API access tokens
 * - Revoke current access tokens during logout
 */
class AuthService
{
    /**
     * Create a new AuthService instance.
     */
    public function __construct(
        private AuthRepository $authRepository,
        private LoginAttemptService $loginAttemptService,
        private AuditLogService $auditLogService,
    ) {}

    /**
     * Authenticate a staff user and generate an API token.
     *
     * @param array $data
     * @param string $ip
     * @param string|null $deviceInfo
     *
     * @return array{
     *     token: string,
     *     user: StaffUser
     * }
     */
    public function login(array $data, string $ip, ?string $deviceInfo = null): array
    {
        // Prepare common login attempt data
        $attemptData = [
            'attempted_email' => $data['email'],
            'ip_address'      => $ip,
            'device_info'     => $deviceInfo,
        ];

        $user = $this->authRepository->findByEmail($data['email']);

        if (!$user) {
            $this->loginAttemptService->logFailure([
                ...$attemptData,
                'failure_reason' => 'User not found',
            ]);

            abort(401, __('auth.invalid_credentials'));
        }

        if (!Hash::check($data['password'], $user->password_hash)) {
            $this->loginAttemptService->logFailure([
                ...$attemptData,
                'matched_user_id' => $user->id,
                'failure_reason'  => 'Invalid password',
            ]);

            abort(401, __('auth.invalid_credentials'));
        }

        if (!$user->is_active) {
            $this->loginAttemptService->logFailure([
                ...$attemptData,
                'matched_user_id' => $user->id,
                'failure_reason'  => 'Account inactive',
            ]);

            abort(401, __('auth.account_inactive'));
        }

        $token = $user->createToken(
            'staff-token',
            ['*'],
            now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

        // Successful login attempt
        $this->loginAttemptService->logSuccess([
            ...$attemptData,
            'matched_user_id' => $user->id,
        ]);

        // Audit log
        $this->auditLogService->create([
            'tenant_id'   => $user->tenant_id,
            'user_id'     => $user->id,
            'clinic_id'   => $user->home_clinic_id,
            'log_type'    => 'login_event',
            'entity_type' => 'staff_user',
            'entity_id'   => $user->id,
            'action'      => 'User logged in',
            'ip_address'  => $ip,
            'device_info' => $deviceInfo,
        ]);

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }

    /**
     * Log out the authenticated user by revoking
     * the currently active access token.
     */
    public function logout(StaffUser $user): void
    {
        $token = $user->currentAccessToken();

        if ($token) {
            $user->tokens()
                ->where('id', $token->id)
                ->delete();
        }
    }
}
