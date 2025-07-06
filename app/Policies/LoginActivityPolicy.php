<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LoginActivity;

class LoginActivityPolicy
{
    public function view(User $user, LoginActivity $log): bool
    {
        return $user->id === $log->user_id
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }
}
