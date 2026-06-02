<?php

namespace App\Services;

use App\Models\StaffUser;
use App\Repositories\StaffUserRepository;
use Illuminate\Support\Collection;

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
}