<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SiteSetting;

class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin', 'Admin']);
    }

    public function view(User $user, SiteSetting $setting): bool
    {
        if ($user->hasRole('Developer')) {
            return true;
        }

        if ($user->hasRole('Global Admin')) {
            return in_array($setting->access_level, ['global_admin', 'admin']);
        }

        if ($user->hasRole('Admin')) {
            return $setting->access_level === 'admin' || $setting->is_public;
        }

        return $setting->is_public;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Developer');
    }

    public function update(User $user, SiteSetting $setting): bool
    {
        if ($user->hasRole('Developer')) {
            return true;
        }

        if ($user->hasRole('Global Admin')) {
            return in_array($setting->access_level, ['global_admin', 'admin']);
        }

        if ($user->hasRole('Admin')) {
            return $setting->access_level === 'admin';
        }

        return false;
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return $user->hasRole('Developer');
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
