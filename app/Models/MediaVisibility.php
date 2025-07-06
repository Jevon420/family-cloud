<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Traits\TracksAudit;

class MediaVisibility extends Model
{
    use HasFactory, SoftDeletes, TracksAudit;

    protected $table = 'media_visibility';

    protected $fillable = [
        'media_type',
        'media_id',
        'visibility',
        'allow_download',
        'expires_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_at',
        'restored_by',
    ];

    protected $casts = [
        'allow_download' => 'boolean',
        'expires_at' => 'datetime',
        'restored_at' => 'datetime',
    ];

    // Relationships
    public function media()
    {
        return $this->morphTo();
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
