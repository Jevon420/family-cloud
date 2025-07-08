<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Traits\TracksAudit;

class SharedMedia extends Model
{
    use HasFactory, SoftDeletes, TracksAudit;

    protected $table = 'shared_media';

    protected $fillable = [
        'media_type',
        'media_id',
        'shared_by',
        'shared_with',
        'share_token',
        'permissions',
        'expires_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_at',
        'restored_by',
    ];

    protected $casts = [
        'permissions' => 'array',
        'expires_at' => 'datetime',
        'restored_at' => 'datetime',
    ];

    // Relationships
    public function media()
    {
        return $this->morphTo();
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function restorer()
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    // Scopes
    public function scopeActiveShares($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeSharedWithUser($query, $userId)
    {
        return $query->where('shared_with', $userId);
    }

    public function scopeSharedByUser($query, $userId)
    {
        return $query->where('shared_by', $userId);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }
}
