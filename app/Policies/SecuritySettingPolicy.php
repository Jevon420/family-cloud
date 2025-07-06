<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SecuritySetting;

class SecuritySettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }

    public function view(User $user, SecuritySetting $setting): bool
    {
        if ($user->hasRole('Developer')) {
            return true;
        }

        if ($user->hasRole('Global Admin')) {
            return $setting->access_level === 'global_admin';
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Developer');
    }

    public function update(User $user, SecuritySetting $setting): bool
    {
        if ($user->hasRole('Developer')) {
            return true;
        }

        if ($user->hasRole('Global Admin')) {
            return $setting->access_level === 'global_admin' && !$setting->is_critical;
        }

        return false;
    }

    public function delete(User $user, SecuritySetting $setting): bool
    {
        return $user->hasRole('Developer');
    }

    public function restore(User $user, SecuritySetting $setting): bool
    {
        return $user->hasRole('Developer');
    }

    public function forceDelete(User $user, SecuritySetting $setting): bool
    {
        return $user->hasRole('Developer');
    }
}
