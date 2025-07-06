<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksAudit;
use App\Models\PageContent;
use App\Models\PageImage;

class Page extends Model
{
    use HasFactory, SoftDeletes, TracksAudit;

    protected $fillable = [
        'name', 'slug', 'meta_description', 'is_published',
        'created_by', 'updated_by', 'deleted_by', 'restored_by', 'restored_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'restored_at' => 'datetime',
    ];

    public function contents()
    {
        return $this->hasMany(PageContent::class);
    }

    public function images()
    {
        return $this->hasMany(PageImage::class);
    }

    public function getContent($key, $fallback = '')
    {
        return optional($this->contents->where('key', $key)->first())->value ?? $fallback;
    }

    public function getImagePath($label, $fallback = '')
    {
        return optional($this->images->where('label', $label)->first())->path ?? $fallback;
    }
}
