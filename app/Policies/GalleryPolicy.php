<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Gallery;
use App\Models\Announcement;

class GalleryPolicy
{
    public function view(User $user, Gallery $gallery): bool
    {
        if ($user->can('view all galleries')) {
            return true;
        }

        if ($user->can('view own galleries') && $gallery->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create galleries');
    }

    public function update(User $user, Gallery $gallery): bool
    {
        if ($user->can('edit galleries') && $gallery->user_id === $user->id) {
            return true;
        }

        return $user->can('edit galleries') && $user->hasRole(['Admin', 'Developer', 'Global Admin']);
    }

    public function delete(User $user, Gallery $gallery): bool
    {
        if ($user->can('delete galleries') && $gallery->user_id === $user->id) {
            return true;
        }

        return $user->can('delete galleries') && $user->hasRole(['Admin', 'Developer', 'Global Admin']);
    }

    public function forceDelete(User $user, Announcement $photo): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }
    public function restore(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->created_by
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }
}
