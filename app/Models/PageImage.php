<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksAudit;
use App\Models\PageContent;
use App\Models\Page;

class PageImage extends Model
{
    use HasFactory, SoftDeletes, TracksAudit;

    protected $fillable = [
        'page_id', 'label', 'path', 'alt_text',
        'created_by', 'updated_by', 'deleted_by', 'restored_by', 'restored_at',
    ];

    protected $casts = [
        'restored_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
