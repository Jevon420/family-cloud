<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SharedItem;

class SharedItemPolicy
{
    public function view(User $user, SharedItem $sharedItem): bool
    {
        // Must have permission and be the intended recipient
        if (! $user->can('view shared content')) {
            return false;
        }

        if ($sharedItem->shared_with_user_id !== $user->id) {
            return false;
        }

        // Check if expired
        if ($sharedItem->expires_at && $sharedItem->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
