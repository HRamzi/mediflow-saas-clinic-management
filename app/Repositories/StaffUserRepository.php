<?php

namespace App\Repositories;

use App\Models\StaffUser;
use Illuminate\Support\Collection;

class StaffUserRepository
{
    /**
     * Retrieve all staff users belonging to a tenant.
     *
     * @param string $tenantId The tenant identifier.
     *
     * @return Collection<int, StaffUser>
     */
    public function getAll(string $tenantId): Collection
    {
        return StaffUser::where('tenant_id', $tenantId)->get();
    }
}