<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SiteSetting;

class SiteSettingPolicy
{
    public function view(User $user, SiteSetting $setting): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }

    public function update(User $user, SiteSetting $setting): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return $user->hasRole('Developer'); // Optional: restrict delete to Developer
    }

    public function forceDelete(User $user, SiteSetting $setting): bool
    {
        return $user->hasRole('Developer');
    }
    public function restore(User $user, SiteSetting $setting): bool
    {
        return $user->hasRole('Developer');
    }
}
