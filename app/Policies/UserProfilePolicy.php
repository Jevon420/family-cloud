<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\SiteSetting;

class UserProfilePolicy
{
    public function view(User $user, UserProfile $profile): bool
    {
        if ($user->id === $profile->user_id) {
            return true;
        }

        return $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function update(User $user, UserProfile $profile): bool
    {
        if ($user->id === $profile->user_id) {
            return true;
        }

        return $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function delete(User $user, UserProfile $profile): bool
    {
        return $user->hasRole('Developer'); // optional: only developers can delete profiles
    }
    public function forceDelete(User $user, SiteSetting $setting): bool
    {
        return $user->hasAnyRole('Developer');
    }
    public function restore(User $user, SiteSetting $setting): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }
}
