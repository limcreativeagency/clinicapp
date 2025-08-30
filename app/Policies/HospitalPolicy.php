<?php

namespace App\Policies;

use App\Models\Hospital;
use App\Models\User;

class HospitalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin() || 
               ($user->isAdmin() && $user->hospital_id === $hospital->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin() || 
               ($user->isAdmin() && $user->hospital_id === $hospital->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin();
    }
}
