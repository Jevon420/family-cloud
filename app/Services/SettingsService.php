<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\SystemConfiguration;
use App\Models\SecuritySetting;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected $cachePrefix = 'settings:';
    protected $cacheTtl = 3600; // 1 hour

    public function getSiteSetting($key, $default = null)
    {
        $cacheKey = $this->cachePrefix . 'site:' . $key;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($key, $default) {
            $setting = SiteSetting::where('key', $key)->first();
            return $setting ? $this->castValue($setting->value, $setting->type) : $default;
        });
    }

    public function setSiteSetting($key, $value, $type = 'string')
    {
        $setting = SiteSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_by' => auth()->id()
            ]
        );

        // Clear cache
        $cacheKey = $this->cachePrefix . 'site:' . $key;
        Cache::forget($cacheKey);

        return $setting;
    }

    public function getSystemConfiguration($key, $default = null)
    {
        $cacheKey = $this->cachePrefix . 'system:' . $key;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($key, $default) {
            $setting = SystemConfiguration::where('key', $key)->first();
            return $setting ? $this->castValue($setting->value, $setting->type) : $default;
        });
    }

    public function setSystemConfiguration($key, $value, $type = 'string')
    {
        $setting = SystemConfiguration::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_by' => auth()->id()
            ]
        );

        // Clear cache
        $cacheKey = $this->cachePrefix . 'system:' . $key;
        Cache::forget($cacheKey);

        return $setting;
    }

    public function getSecuritySetting($key, $default = null)
    {
        $cacheKey = $this->cachePrefix . 'security:' . $key;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($key, $default) {
            $setting = SecuritySetting::where('key', $key)->first();
            return $setting ? $this->castValue($setting->value, $setting->type) : $default;
        });
    }

    public function setSecuritySetting($key, $value, $type = 'string')
    {
        $setting = SecuritySetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_by' => auth()->id()
            ]
        );

        // Clear cache
        $cacheKey = $this->cachePrefix . 'security:' . $key;
        Cache::forget($cacheKey);

        return $setting;
    }

    public function getUserSetting($userId, $key, $default = null)
    {
        $cacheKey = $this->cachePrefix . 'user:' . $userId . ':' . $key;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($userId, $key, $default) {
            $setting = UserSetting::where('user_id', $userId)->where('key', $key)->first();
            return $setting ? $this->castValue($setting->value, $setting->type) : $default;
        });
    }

    public function setUserSetting($userId, $key, $value, $type = 'string')
    {
        $setting = UserSetting::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_by' => auth()->id()
            ]
        );

        // Clear cache
        $cacheKey = $this->cachePrefix . 'user:' . $userId . ':' . $key;
        Cache::forget($cacheKey);

        return $setting;
    }

    public function clearAllSettingsCache()
    {
        $tags = ['settings:site', 'settings:system', 'settings:security', 'settings:user'];

        foreach ($tags as $tag) {
            Cache::flush(); // Simple flush for now, could be more targeted with cache tags
        }
    }

    public function getAllSiteSettings()
    {
        return SiteSetting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $this->castValue($setting->value, $setting->type)];
        });
    }

    public function getAllSystemConfigurations()
    {
        return SystemConfiguration::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $this->castValue($setting->value, $setting->type)];
        });
    }

    public function getAllSecuritySettings()
    {
        return SecuritySetting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $this->castValue($setting->value, $setting->type)];
        });
    }

    public function isMaintenanceMode()
    {
        return $this->getSiteSetting('maintenance_mode', false);
    }

    public function isRegistrationEnabled()
    {
        return $this->getSiteSetting('registration_enabled', true);
    }

    public function getMaxFileUploadSize()
    {
        return $this->getSiteSetting('max_file_upload_size', 50); // MB
    }

    public function getMaxStoragePerUser()
    {
        return $this->getSiteSetting('max_storage_per_user', 5000); // MB
    }

    public function getAllowedFileTypes()
    {
        $types = $this->getSiteSetting('allowed_file_types', 'jpg,jpeg,png,gif,pdf,doc,docx,txt');
        return explode(',', $types);
    }

    protected function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float)$value : 0;
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }
}
