<?php

namespace App\Policies;

use App\Models\MediaVisibility;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaVisibilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any media visibility records.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view media visibility');
    }

    /**
     * Determine whether the user can view the media visibility record.
     */
    public function view(User $user, MediaVisibility $mediaVisibility): bool
    {
        return $user->hasPermissionTo('view media visibility');
    }

    /**
     * Determine whether the user can create media visibility records.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create media visibility');
    }

    /**
     * Determine whether the user can update the media visibility record.
     */
    public function update(User $user, MediaVisibility $mediaVisibility): bool
    {
        return $user->hasPermissionTo('update media visibility');
    }

    /**
     * Determine whether the user can delete the media visibility record.
     */
    public function delete(User $user, MediaVisibility $mediaVisibility): bool
    {
        return $user->hasPermissionTo('delete media visibility');
    }

    /**
     * Determine whether the user can restore the media visibility record.
     */
    public function restore(User $user, MediaVisibility $mediaVisibility): bool
    {
        return $user->hasPermissionTo('restore media visibility');
    }

    /**
     * Determine whether the user can permanently delete the media visibility record.
     */
    public function forceDelete(User $user, MediaVisibility $mediaVisibility): bool
    {
        return $user->hasPermissionTo('force delete media visibility');
    }
}
