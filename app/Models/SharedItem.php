<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Tag;
use App\Models\MediaVisibility;
use App\Traits\TracksAudit;

class SharedItem extends Model
{
    use HasFactory, SoftDeletes, TracksAudit;

    protected $table = 'shared_items';

    protected $fillable = [
        'shared_type',
        'shared_id',
        'shared_with_user_id',
        'access_level',
        'expires_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_at',
        'restored_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'restored_at' => 'datetime',
    ];

    // Relationships
    public function shared()
    {
        return $this->morphTo();
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
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
}
