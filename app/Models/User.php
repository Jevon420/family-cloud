<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksAudit;
use App\Services\StorageManagementService;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, SoftDeletes, TracksAudit;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'password_change_required',
        'storage_quota_gb',
        'storage_used_gb',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    public function downloads()
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user) {
            // Assign the default "family" role to new users
            $user->assignRole('family');
        });
    }

    /**
     * Get user's storage usage percentage
     */
    public function getStorageUsagePercentage()
    {
        if ($this->storage_quota_gb <= 0) {
            return 0;
        }

        return round(($this->storage_used_gb / $this->storage_quota_gb) * 100, 2);
    }

    /**
     * Get user's available storage in GB
     */
    public function getAvailableStorageGB()
    {
        return max(0, $this->storage_quota_gb - $this->storage_used_gb);
    }

    /**
     * Format storage size for display
     */
    public function formatStorageSize($sizeGB)
    {
        if ($sizeGB >= 1) {
            return number_format($sizeGB, 2) . ' GB';
        } else {
            return number_format($sizeGB * 1024, 2) . ' MB';
        }
    }

    /**
     * Get formatted storage quota
     */
    public function getFormattedQuota()
    {
        return $this->formatStorageSize($this->storage_quota_gb);
    }

    /**
     * Get formatted storage used
     */
    public function getFormattedUsed()
    {
        return $this->formatStorageSize($this->storage_used_gb);
    }

    /**
     * Get formatted available storage
     */
    public function getFormattedAvailable()
    {
        return $this->formatStorageSize($this->getAvailableStorageGB());
    }

    //Get user Role
    public function getRole()
    {
        return $this->roles->first();
    }

    /**
     * Calculate storage used by the user in GB.
     */
    public function calculateStorageUsedGB()
    {
        $photosSize = $this->photos()->sum('file_size');
        $filesSize = $this->files()->sum('file_size');
        $foldersSize = $this->folders()->sum('file_size');

        // Include gallery cover images and thumbnails
        $galleryCoverSize = $this->galleries()->sum('cover_image_size');
        $thumbnailSize = $this->photos()->sum('thumbnail_size');

        $totalSizeBytes = $photosSize + $filesSize + $foldersSize + $galleryCoverSize + $thumbnailSize;

        return round($totalSizeBytes / (1024 * 1024 * 1024), 2); // Convert to GB
    }
}
