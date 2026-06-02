<?php

namespace App\Services;

use App\Models\StaffUser;
use App\Repositories\StaffUserRepository;
use Illuminate\Support\Collection;

/**
 * Handles staff user business logic.
 *
 * Responsibilities:
 * - Retrieve staff users belonging to a tenant.
 * - Unlock deactivated staff user accounts.
 */
class StaffUserService
{
    /**
     * Create a new StaffUserService instance.
     *
     * @param StaffUserRepository $staffUserRepository Repository used to access staff user data.
     */
    public function __construct(
        private StaffUserRepository $staffUserRepository,
    ) {}

    /**
     * Retrieve all staff users for the authenticated user's tenant.
     *
     * @param StaffUser $authUser The authenticated staff user.
     *
     * @return Collection<int, StaffUser>
     */
    public function getAll(StaffUser $authUser): Collection
    {
        return $this->staffUserRepository->getAll(
            $authUser->tenant_id
        );
    }

    /**
     * Unlock a staff user account.
     *
     * This method reactivates a previously locked or
     * deactivated account by setting the is_active flag
     * back to true.
     *
     * @param string $staffUserId The ID of the staff user to unlock.
     *
     * @return void
     */
    public function unlock(string $staffUserId): void
    {
        $user = StaffUser::withoutGlobalScopes()
            ->where('id', $staffUserId)
            ->first();

        $user->update([
            'is_active' => true,
        ]);
    }
}