<?php

namespace App\Policies;

use App\Models\User;
use App\Models\File;

class FilePolicy
{
    public function view(User $user, File $file): bool
    {
        if ($user->can('view all files')) {
            return true;
        }

        if ($user->can('view own files') && $file->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('upload files');
    }

    public function delete(User $user, File $file): bool
    {
        if ($user->can('delete files') && $file->user_id === $user->id) {
            return true;
        }

        return $user->can('delete files') && $user->hasRole(['Admin', 'Developer', 'Global Admin']);
    }
    public function forceDelete(User $user, File $file): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }
    public function restore(User $user, File $file): bool
    {
        return $user->id === $file->user_id
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }
}
