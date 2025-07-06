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
}
