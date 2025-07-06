<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        \App\Models\Gallery::class => \App\Policies\GalleryPolicy::class,
        \App\Models\Photo::class => \App\Policies\PhotoPolicy::class,
        \App\Models\File::class => \App\Policies\FilePolicy::class,
        \App\Models\Folder::class => \App\Policies\FolderPolicy::class,
        \App\Models\SharedItem::class => \App\Policies\SharedItemPolicy::class,
        \App\Models\UserProfile::class => \App\Policies\UserProfilePolicy::class,
        \App\Models\UserSetting::class => \App\Policies\UserSettingPolicy::class,
        \App\Models\SiteSetting::class => \App\Policies\SiteSettingPolicy::class,
        \App\Models\Announcement::class => \App\Policies\AnnouncementPolicy::class,
        \App\Models\ContactMessage::class => \App\Policies\ContactMessagePolicy::class,
        \App\Models\MediaVisibility::class => \App\Policies\MediaVisibilityPolicy::class,
        \App\Models\DownloadLog::class => \App\Policies\DownloadLogPolicy::class,
        \App\Models\LoginActivity::class => \App\Policies\LoginActivityPolicy::class,
        \App\Models\Tag::class => \App\Policies\TagPolicy::class,
        \App\Models\Taggable::class => \App\Policies\TaggablePolicy::class,

        \App\Models\User::class => \App\Policies\UserPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
