<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\StaffUserResource;
use App\Models\StaffUser;
use App\Services\StaffUserService;
use App\Services\AuditLogService;
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
        private StaffUserService $staffUserService,
        private AuditLogService $auditLogService
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

    /**
     * Unlock a staff user account.
     *
     * Only authorized users may unlock accounts.
     * The target staff user is identified by the route parameter.
     *
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function unlock(Request $request, string $id): JsonResponse
    {
        $this->authorize('unlock', StaffUser::class);

        $this->staffUserService->unlock($id);

        $this->auditLogService->create([
            'tenant_id'    => $request->user()->tenant_id,
            'user_id'      => $request->user()->id,
            'clinic_id'    => $request->user()->home_clinic_id,
            'log_type'     => 'admin_change',
            'entity_type'  => StaffUser::class,
            'entity_id'    => $id,
            'action'       => 'unlock_staff_user',
            'new_value'    => [
                'is_active' => true,
            ],
            'ip_address'   => $request->ip(),
            'device_info'  => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => null,
            'message' => 'Staff user unlocked successfully.',
        ]);
    }
}
