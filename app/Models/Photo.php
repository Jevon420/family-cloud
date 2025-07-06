<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Tag;
use App\Models\MediaVisibility;
use App\Models\SharedItem;
use App\Models\DownloadLog;
use App\Traits\TracksAudit;
use App\Traits\HasMediaVisibility;

class Photo extends Model
{
    use HasFactory, SoftDeletes, TracksAudit, HasMediaVisibility;

    protected $fillable = [
        'user_id',
        'gallery_id',
        'name',
        'slug',
        'file_path',
        'thumbnail_path',
        'description',
        'visibility',
        'mime_type',
        'file_size',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_at',
        'restored_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'restored_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function visibility()
    {
        return $this->morphOne(MediaVisibility::class, 'media');
    }

    public function shares()
    {
        return $this->morphMany(SharedItem::class, 'shared');
    }

    public function downloads()
    {
        return $this->morphMany(DownloadLog::class, 'downloadable');
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
