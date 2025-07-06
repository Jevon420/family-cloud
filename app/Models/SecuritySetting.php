<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksAudit;

class SecuritySetting extends Model
{
    use HasFactory, SoftDeletes, TracksAudit;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_critical',
        'validation_rules',
        'access_level',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_at',
        'restored_by',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'validation_rules' => 'array',
        'restored_at' => 'datetime',
    ];

    // Relationships
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
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeAccessibleBy($query, $user)
    {
        if ($user->hasRole('Developer')) {
            return $query;
        }

        if ($user->hasRole('Global Admin')) {
            return $query->where('access_level', 'global_admin');
        }

        return $query->where('access_level', 'admin');
    }

    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    // Helper methods
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value, $type = 'string')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_by' => auth()->id()
            ]
        );
    }
}
