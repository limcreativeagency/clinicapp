<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isDoctor() || $user->isRepresentative();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Kendi profilini her zaman görebilir
        if ($user->id === $model->id) {
            return true;
        }

        // Süper admin herkesi görebilir
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin sadece kendi hastanesindeki kullanıcıları görebilir
        if ($user->isAdmin()) {
            return $user->hospital_id === $model->hospital_id;
        }

        // Doktor ve temsilci sadece kendi hastanesindeki hastaları görebilir
        if ($user->isDoctor() || $user->isRepresentative()) {
            return $user->hospital_id === $model->hospital_id && $model->isPatient();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Kendi profilini her zaman güncelleyebilir
        if ($user->id === $model->id) {
            return true;
        }

        // Süper admin herkesi güncelleyebilir
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin sadece kendi hastanesindeki kullanıcıları güncelleyebilir
        if ($user->isAdmin()) {
            return $user->hospital_id === $model->hospital_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Kendini silemez
        if ($user->id === $model->id) {
            return false;
        }

        // Süper admin herkesi silebilir
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin sadece kendi hastanesindeki kullanıcıları silebilir
        if ($user->isAdmin()) {
            return $user->hospital_id === $model->hospital_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isSuperAdmin() || 
               ($user->isAdmin() && $user->hospital_id === $model->hospital_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
