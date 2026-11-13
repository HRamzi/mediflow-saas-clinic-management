<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\Auth\StaffUserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles authentication-related HTTP requests.
 *
 * Responsibilities:
 * - Authenticate staff users
 * - Return authenticated user information
 * - Revoke access tokens during logout
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $authService Service responsible for authentication logic.
     */
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Authenticate a staff user and return an access token.
     *
     * Validates the incoming credentials, delegates authentication
     * to the AuthService, and returns the authenticated user
     * along with a newly generated API token.
     *
     * @param LoginRequest $request Validated login request.
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated(),
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'data' => new AuthResource(
                $result['user'],
                $result['token']
            ),
            'message' => __('auth.login_success')
        ]);
    }

    /**
     * Log out the authenticated user.
     *
     * Revokes the currently active access token,
     * preventing further authenticated requests
     * using that token.
     *
     * @param Request $request Current authenticated request.
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout(
            $request->user()
        );

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => __('auth.logout_success')
        ]);
    }

    /**
     * Retrieve the currently authenticated user.
     *
     * Returns the authenticated staff user's profile
     * information using the StaffUserResource.
     *
     * @param Request $request Current authenticated request.
     *
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new StaffUserResource(
                $request->user()
            ),

            'message' => __('auth.profile_retrieved'),
        ]);
    }
}
