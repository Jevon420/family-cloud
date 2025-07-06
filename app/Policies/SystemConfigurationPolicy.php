<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SystemConfiguration;

class SystemConfigurationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin', 'Admin']);
    }

    public function view(User $user, SystemConfiguration $configuration): bool
    {
        if ($user->hasRole('Developer')) {
            return true;
        }

        if ($user->hasRole('Global Admin')) {
            return in_array($configuration->access_level, ['global_admin', 'admin']);
        }

        if ($user->hasRole('Admin')) {
            return $configuration->access_level === 'admin';
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Developer');
    }

    public function update(User $user, SystemConfiguration $configuration): bool
    {
        if ($user->hasRole('Developer')) {
            return true;
        }

        if ($user->hasRole('Global Admin')) {
            return in_array($configuration->access_level, ['global_admin', 'admin']) && !$configuration->is_sensitive;
        }

        return false;
    }

    public function delete(User $user, SystemConfiguration $configuration): bool
    {
        return $user->hasRole('Developer');
    }

    public function restore(User $user, SystemConfiguration $configuration): bool
    {
        return $user->hasRole('Developer');
    }

    public function forceDelete(User $user, SystemConfiguration $configuration): bool
    {
        return $user->hasRole('Developer');
    }
}
