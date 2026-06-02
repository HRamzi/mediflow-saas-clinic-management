<?php

namespace App\Policies;

use App\Models\StaffUser;

class StaffUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(StaffUser $user): bool
    {
        return in_array($user->role, [
            'owner',
            'head_receptionist',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(StaffUser $user, StaffUser $model): bool
    {
        return in_array($user->role, [
            'owner',
            'head_receptionist',
        ]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(StaffUser $user): bool
    {
        return in_array($user->role, [
            'owner',
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(StaffUser $user, StaffUser $model): bool
    {
        return in_array($user->role, [
            'owner',
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(StaffUser $user, StaffUser $model): bool
    {
        return in_array($user->role, [
            'owner',
        ]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(StaffUser $user, StaffUser $model): bool
    {
        return in_array($user->role, [
            'owner',
        ]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(StaffUser $user, StaffUser $model): bool
    {
        return in_array($user->role, [
            'owner',
        ]);
    }
}
