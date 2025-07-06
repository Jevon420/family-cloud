<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;

class TagPolicy
{
    public function view(User $user, Tag $tag): bool
    {
        return true; // Tags are public
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->hasAnyRole(['Admin', 'Global Admin', 'Developer']);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasRole('Developer'); // Optional strict delete
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $user->hasRole('Developer');
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $user->hasRole('Developer');
    }
}
