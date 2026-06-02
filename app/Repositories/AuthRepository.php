<?php

namespace App\Repositories;

use App\Models\StaffUser;

class AuthRepository
{
    /**
     * Find a staff user by email address.
     */
    public function findByEmail(string $email): ?StaffUser
    {
        return StaffUser::where('email', $email)->first();
    }
}