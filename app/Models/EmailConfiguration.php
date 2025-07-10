<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class EmailConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'email_configurations';
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'is_default',
        'is_active',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'pop_host',
        'pop_port',
        'pop_encryption',
        'pop_username',
        'from_name',
        'signature',
        'settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'smtp_port' => 'integer',
        'imap_port' => 'integer',
        'pop_port' => 'integer',
        'settings' => 'array',
    ];

    protected $hidden = [
        'password',
    ];

    // Encrypt password when storing
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    // Decrypt password when retrieving
    public function getPasswordAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    // Relationships
    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeOutgoing($query)
    {
        return $query->whereIn('type', ['outgoing', 'both']);
    }

    public function scopeIncoming($query)
    {
        return $query->whereIn('type', ['incoming', 'both']);
    }

    // Helper methods
    public function canSendEmail()
    {
        return in_array($this->type, ['outgoing', 'both']) && $this->is_active;
    }

    public function canReceiveEmail()
    {
        return in_array($this->type, ['incoming', 'both']) && $this->is_active;
    }

    public function getSmtpConfig()
    {
        return [
            'host' => $this->smtp_host,
            'port' => $this->smtp_port,
            'encryption' => $this->smtp_encryption,
            'username' => $this->smtp_username ?: $this->email,
            'password' => $this->password,
            'from' => [
                'address' => $this->email,
                'name' => $this->from_name ?: $this->name,
            ],
        ];
    }

    public function getImapConfig()
    {
        return [
            'host' => $this->imap_host,
            'port' => $this->imap_port,
            'encryption' => $this->imap_encryption,
            'username' => $this->imap_username ?: $this->email,
            'password' => $this->password,
        ];
    }

    public function getPopConfig()
    {
        return [
            'host' => $this->pop_host,
            'port' => $this->pop_port,
            'encryption' => $this->pop_encryption,
            'username' => $this->pop_username ?: $this->email,
            'password' => $this->password,
        ];
    }

    // Boot method to handle default email logic
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // If this is being set as default, remove default from others
            if ($model->is_default && $model->canSendEmail()) {
                static::where('is_default', true)
                    ->where('id', '!=', $model->id)
                    ->whereIn('type', ['outgoing', 'both'])
                    ->update(['is_default' => false]);
            }
        });
    }
}
