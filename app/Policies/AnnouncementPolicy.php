<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;

class AnnouncementPolicy
{
    public function view(User $user, Announcement $announcement): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin', 'Admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->created_by
            || $user->hasAnyRole(['Developer', 'Global Admin']);
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->hasRole('Developer');
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
