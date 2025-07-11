<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Folder;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\UserProfile;
use App\Models\UserSetting;
use App\Models\SiteSetting;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\SharedItem;
use App\Models\MediaVisibility;
use App\Models\DownloadLog;
use App\Models\LoginActivity;
use App\Models\Tag;
use App\Models\Taggable;

use App\Observers\AuditObserver;
use App\Observers\FileObserver;
use App\Observers\PhotoObserver;
use App\Traits\TracksAudit;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Console\Commands\UpdateUserStorageCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Services\SettingsService::class, function ($app) {
            return new \App\Services\SettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        File::observe(FileObserver::class);
        Photo::observe(PhotoObserver::class);

        /* $auditedModels = [
            File::class,
            Folder::class,
            Gallery::class,
            Photo::class,
            UserProfile::class,
            UserSetting::class,
            SiteSetting::class,
            Announcement::class,
            ContactMessage::class,
            SharedItem::class,
            MediaVisibility::class,
            DownloadLog::class,
            LoginActivity::class,
            Tag::class,
            Taggable::class,
        ];

        foreach ($auditedModels as $model) {
            $model::observe(AuditObserver::class);
        } */
    }
}
