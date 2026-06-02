<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\StaffUserResource;
use App\Models\StaffUser;
use App\Services\StaffUserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffUserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create a new StaffUserController instance.
     *
     * @param StaffUserService $staffUserService Service responsible for staff user operations.
     */
    public function __construct(
        private StaffUserService $staffUserService
    ) {}

    /**
     * Display a listing of staff users for the authenticated user's tenant.
     *
     * Authorization:
     * - Requires the user to pass the StaffUserPolicy::viewAny check.
     *
     * @param Request $request The incoming HTTP request.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Authorize access to the staff user listing.
        $this->authorize('viewAny', StaffUser::class);

        // Retrieve staff users for the authenticated user's tenant.
        $staffUsers = $this->staffUserService->getAll(
            $request->user()
        );

        // Return the collection as API resources.
        return response()->json([
            'success' => true,
            'data' => StaffUserResource::collection($staffUsers),
            'message' => 'Staff users retrieved successfully.',
        ]);
    }
}