<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSetting;

class UserSettingPolicy
{
    public function view(User $user, UserSetting $setting): bool
    {
        return $user->id === $setting->user_id || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function update(User $user, UserSetting $setting): bool
    {
        return $user->id === $setting->user_id || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function delete(User $user, UserSetting $setting): bool
    {
        return $user->id === $setting->user_id || $user->hasAnyRole(['Global Admin', 'Developer']);
    }
    public function forceDelete(User $user, UserSetting $setting): bool
    {
        return $user->hasRole('Developer');
    }
    public function restore(User $user, UserSetting $setting): bool
    {
        return $user->hasAnyRole(['Global Admin', 'Developer']);
    }
}
