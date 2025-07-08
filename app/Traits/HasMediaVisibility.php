<?php

namespace App\Traits;

trait HasMediaVisibility
{
    /**
     * Scope a query to only include public media.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->whereHas('visibility', function($q) {
            $q->where('visibility', 'public');
        });
    }

    /**
     * Scope a query to only include private media.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrivate($query)
    {
        return $query->whereHas('visibility', function($q) {
            $q->where('visibility', 'private');
        });
    }

    /**
     * Scope a query to only include shared media.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShared($query)
    {
        return $query->whereHas('visibility', function($q) {
            $q->where('visibility', 'shared');
        });
    }

    /**
     * Scope a query to only include link-accessible media.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithLink($query)
    {
        return $query->whereHas('visibility', function($q) {
            $q->where('visibility', 'link');
        });
    }

    /**
     * Scope a query to include media with a specific visibility.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $visibility
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithVisibility($query, $visibility)
    {
        return $query->whereHas('visibility', function($q) use ($visibility) {
            $q->where('visibility', $visibility);
        });
    }

    /**
     * Check if media is public.
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->visibility && $this->visibility->visibility === 'public';
    }

    /**
     * Check if media is private.
     *
     * @return bool
     */
    public function isPrivate()
    {
        return $this->visibility && $this->visibility->visibility === 'private';
    }

    /**
     * Check if media is shared.
     *
     * @return bool
     */
    public function isShared()
    {
        return $this->visibility && $this->visibility->visibility === 'shared';
    }

    /**
     * Check if media has link access.
     *
     * @return bool
     */
    public function hasLinkAccess()
    {
        return $this->visibility && $this->visibility->visibility === 'link';
    }

    /**
     * Get the visibility relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function visibility()
    {
        return $this->morphOne('App\Models\MediaVisibility', 'media');
    }

    /**
     * Get the shared media relationships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sharedMedia()
    {
        return $this->morphMany('App\Models\SharedMedia', 'media');
    }

    /**
     * Scope for media shared with a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSharedWithUser($query, $userId)
    {
        return $query->whereHas('sharedMedia', function($q) use ($userId) {
            $q->where('shared_with', $userId)
              ->where(function ($query) {
                  $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
              });
        });
    }

    /**
     * Check if media is shared with a specific user.
     *
     * @param  int  $userId
     * @return bool
     */
    public function isSharedWithUser($userId)
    {
        return $this->sharedMedia()
            ->where('shared_with', $userId)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Share media with a user.
     *
     * @param  int  $userId
     * @param  array  $permissions
     * @param  \DateTime|null  $expiresAt
     * @return \App\Models\SharedMedia
     */
    public function shareWithUser($userId, $permissions = ['view'], $expiresAt = null)
    {
        return $this->sharedMedia()->create([
            'shared_by' => auth()->id(),
            'shared_with' => $userId,
            'permissions' => $permissions,
            'expires_at' => $expiresAt,
            'share_token' => \Str::random(32),
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Generate a shareable link.
     *
     * @param  \DateTime|null  $expiresAt
     * @return string
     */
    public function generateShareableLink($expiresAt = null)
    {
        $this->visibility()->updateOrCreate(
            ['media_type' => get_class($this), 'media_id' => $this->id],
            [
                'visibility' => 'link',
                'share_token' => \Str::random(32),
                'expires_at' => $expiresAt,
                'created_by' => auth()->id(),
            ]
        );

        return route('shared.link', $this->visibility->share_token);
    }
}
