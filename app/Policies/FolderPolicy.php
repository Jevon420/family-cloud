<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Folder;

class FolderPolicy
{
    public function view(User $user, Folder $folder): bool
    {
        if ($user->can('view all folders')) {
            return true;
        }

        if ($user->can('view own folders') && $folder->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create folders');
    }

    public function delete(User $user, Folder $folder): bool
    {
        if ($user->can('delete folders') && $folder->user_id === $user->id) {
            return true;
        }

        return $user->can('delete folders') && $user->hasRole(['Admin', 'Developer', 'Global Admin']);
    }

    public function forceDelete(User $user, Folder $folder): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }
    public function restore(User $user, Folder $folder): bool
    {
        return $user->id === $folder->user_id
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }
}
