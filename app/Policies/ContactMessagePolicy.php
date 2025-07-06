<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ContactMessage;
use App\Models\Photo;

class ContactMessagePolicy
{
    public function view(User $user, ContactMessage $message): bool
    {
        return $user->id === $message->user_id
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can submit
    }

    public function forceDelete(User $user, ContactMessage $message): bool
    {
        return $user->hasAnyRole(['Developer', 'Global Admin']);
    }
    public function restore(User $user, ContactMessage $message): bool
    {
        return $user->id === $message->user_id
            || $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }
}
