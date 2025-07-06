<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Photo;

class PhotoPolicy
{
    public function view(User $user, Photo $photo): bool
    {
        if ($user->can('view all photos')) {
            return true;
        }

        if ($user->can('view own photos') && $photo->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('upload photos');
    }

    public function delete(User $user, Photo $photo): bool
    {
        if ($user->can('delete photos') && $photo->user_id === $user->id) {
            return true;
        }

        return $user->can('delete photos') && $user->hasRole(['Admin', 'Developer', 'Global Admin']);
    }

    public function forceDelete(User $user, Photo $photo): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }
    public function restore(User $user, Photo $photo): bool
    {
        return $user->id === $photo->user_id
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }
}
