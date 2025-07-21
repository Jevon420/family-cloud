<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile, or if they have an admin role
        return $user->id === $model->id || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile, or if they have an admin role
        return $user->id === $model->id || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only developers can delete users
        return $user->hasRole('Developer');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only developers and global admins can restore users
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only developers can force delete users
        return $user->hasRole('Developer');
    }
}
