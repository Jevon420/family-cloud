<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TracksAudit
{
    public static function bootTracksAudit()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check() && ! $model->isForceDeleting()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });

        static::restoring(function ($model) {
            if (Auth::check()) {
                $model->restored_by = Auth::id();
                $model->restored_at = now();
            }
        });
    }
}
