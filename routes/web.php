<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\AboutController as FrontendAboutController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\Frontend\PhotoController as FrontendPhotoController;
use App\Http\Controllers\Frontend\FileController as FrontendFileController;
use App\Http\Controllers\Frontend\FolderController as FrontendFolderController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\PasswordChangeController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Routes (No Auth Required)
Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
Route::get('/about', [FrontendAboutController::class, 'index'])->name('about');
Route::get('/contact', [FrontendContactController::class, 'index'])->name('contact');
Route::post('/contact', [FrontendContactController::class, 'store'])->name('contact.store');

// Add GET route for logout that redirects to POST for easier user experience
Route::get('/logout', function() {
    return view('auth.logout');
})->name('logout.get');

// Terms of Service, Privacy Policy, and Cookie Policy
Route::get('/terms-of-service', [\App\Http\Controllers\Frontend\TermsOfServiceController::class, 'show'])->name('terms-of-service');
Route::get('/privacy-policy', [\App\Http\Controllers\Frontend\PrivacyPolicyController::class, 'show'])->name('privacy-policy');
Route::get('/cookie-policy', [\App\Http\Controllers\Frontend\CookiePolicyController::class, 'show'])->name('cookie-policy');

// Auth Required Routes
Route::middleware(['auth', 'password.change.required'])->group(function () {
    // Public Galleries, Photos, Files, and Folders (Auth Required)
    Route::prefix('galleries')->name('galleries.')->group(function () {
        Route::get('/', [FrontendGalleryController::class, 'index'])->name('index');
        Route::get('/{slug}', [FrontendGalleryController::class, 'show'])->name('show');
    });

    Route::prefix('photos')->name('photos.')->group(function () {
        Route::get('/', [FrontendPhotoController::class, 'index'])->name('index');
        Route::get('/{id}', [FrontendPhotoController::class, 'show'])->name('show');
    });

    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [FrontendFileController::class, 'index'])->name('index');
        Route::get('/{id}', [FrontendFileController::class, 'show'])->name('show');
        Route::get('/{id}/download', [FrontendFileController::class, 'download'])->name('download');
    });

    Route::prefix('folders')->name('folders.')->group(function () {
        Route::get('/', [FrontendFolderController::class, 'index'])->name('index');
        Route::get('/{id}', [FrontendFolderController::class, 'show'])->name('show');
    });
});

// Shared Routes (Authenticated Users)
Route::middleware(['auth', 'password.change.required'])->prefix('shared')->name('shared.')->group(function () {
    Route::prefix('galleries')->name('galleries.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Shared\GalleryController::class, 'index'])->name('index');
        Route::get('/{slug}', [\App\Http\Controllers\Shared\GalleryController::class, 'show'])->name('show');
    });

    Route::prefix('photos')->name('photos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Shared\PhotoController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Shared\PhotoController::class, 'show'])->name('show');
    });

    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Shared\FileController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Shared\FileController::class, 'show'])->name('show');
        Route::get('/{id}/download', [\App\Http\Controllers\Shared\FileController::class, 'download'])->name('download');
    });

    Route::prefix('folders')->name('folders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Shared\FolderController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Shared\FolderController::class, 'show'])->name('show');
    });
});

// Developer Routes
Route::middleware(['auth', 'password.change.required'])->prefix('developer')->name('developer.')->group(function () {
    Route::middleware(['check.role:Developer'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Developer\HomeController::class, 'index'])->name('home');
        Route::get('/about', [\App\Http\Controllers\Developer\AboutController::class, 'index'])->name('about');
        Route::get('/contact', [\App\Http\Controllers\Developer\ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [\App\Http\Controllers\Developer\ContactController::class, 'store'])->name('contact.store');

        // Developer Settings
        Route::get('/settings', [\App\Http\Controllers\Developer\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Developer\SettingsController::class, 'update'])->name('settings.update');
    });
});

// Admin Routes
Route::middleware(['auth', 'password.change.required'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['check.role:Admin|Developer|Global Admin', 'redirect.user.management'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

        // Admin Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
            Route::get('/users', [\App\Http\Controllers\Admin\SettingsController::class, 'users'])->name('users');
            Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\SettingsController::class, 'updateUserRole'])->name('users.role');
            Route::get('/system', [\App\Http\Controllers\Admin\SettingsController::class, 'system'])->name('system');

            // Comprehensive Settings
            Route::middleware(['check.settings.access'])->group(function () {
                Route::get('/comprehensive', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'index'])->name('comprehensive.index');
                Route::post('/comprehensive/site', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'updateSiteSettings'])->name('comprehensive.site.update');
                Route::post('/comprehensive/system', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'updateSystemConfigurations'])->name('comprehensive.system.update');
                Route::post('/comprehensive/security', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'updateSecuritySettings'])->name('comprehensive.security.update');
                Route::post('/comprehensive/cache/clear', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'clearCache'])->name('comprehensive.cache.clear');
                Route::get('/comprehensive/logs/view', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'viewLogs'])->name('comprehensive.logs.view');
                Route::get('/comprehensive/logs/download', [\App\Http\Controllers\Admin\ComprehensiveSettingsController::class, 'downloadLogs'])->name('comprehensive.logs.download');
            });

            // Temporary test route for timezone debugging
            Route::get('/test-timezone', function () {
                $timezone = \Illuminate\Support\Facades\DB::table('site_settings')->where('key', 'timezone')->value('value');
                $siteSettings = \App\Models\SiteSetting::all();
                $timezoneSetting = \App\Models\SiteSetting::where('key', 'timezone')->first();

                return response()->json([
                    'raw_db_timezone' => $timezone,
                    'all_site_settings' => $siteSettings->pluck('value', 'key'),
                    'timezone_setting_object' => $timezoneSetting,
                    'grouped_site_settings' => $siteSettings->groupBy('group'),
                ]);
            })->name('test.timezone');
        });

        // Admin Galleries Management
        Route::prefix('galleries')->name('galleries.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\GalleryController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('store');
            Route::get('/{gallery}/edit', [\App\Http\Controllers\Admin\GalleryController::class, 'edit'])->name('edit');
            Route::put('/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('destroy');
            Route::get('/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'show'])->name('show');
        });

        // Admin Photos Management
        Route::prefix('photos')->name('photos.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PhotoController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\PhotoController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\PhotoController::class, 'store'])->name('store');
            Route::get('/{photo}/edit', [\App\Http\Controllers\Admin\PhotoController::class, 'edit'])->name('edit');
            Route::put('/{photo}', [\App\Http\Controllers\Admin\PhotoController::class, 'update'])->name('update');
            Route::delete('/{photo}', [\App\Http\Controllers\Admin\PhotoController::class, 'destroy'])->name('destroy');
            Route::get('/{photo}', [\App\Http\Controllers\Admin\PhotoController::class, 'show'])->name('show');
        });

        // Admin Files Management
        Route::prefix('files')->name('files.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FileController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\FileController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\FileController::class, 'store'])->name('store');
            Route::get('/{file}/edit', [\App\Http\Controllers\Admin\FileController::class, 'edit'])->name('edit');
            Route::put('/{file}', [\App\Http\Controllers\Admin\FileController::class, 'update'])->name('update');
            Route::delete('/{file}', [\App\Http\Controllers\Admin\FileController::class, 'destroy'])->name('destroy');
            Route::get('/{file}', [\App\Http\Controllers\Admin\FileController::class, 'show'])->name('show');
            Route::get('/{file}/download', [\App\Http\Controllers\Admin\FileController::class, 'download'])->name('download');
        });

        // Admin Folders Management
        Route::prefix('folders')->name('folders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FolderController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\FolderController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\FolderController::class, 'store'])->name('store');
            Route::get('/{folder}/edit', [\App\Http\Controllers\Admin\FolderController::class, 'edit'])->name('edit');
            Route::put('/{folder}', [\App\Http\Controllers\Admin\FolderController::class, 'update'])->name('update');
            Route::delete('/{folder}', [\App\Http\Controllers\Admin\FolderController::class, 'destroy'])->name('destroy');
            Route::get('/{folder}', [\App\Http\Controllers\Admin\FolderController::class, 'show'])->name('show');
        });

        // Storage management routes (Admin)
        Route::prefix('storage')->name('storage.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\StorageManagementController::class, 'index'])->name('index');
            Route::put('/update', [App\Http\Controllers\Admin\StorageManagementController::class, 'update'])->name('update');
            Route::post('/recalculate', [App\Http\Controllers\Admin\StorageManagementController::class, 'recalculateQuotas'])->name('recalculate');
        });

        // Email Management
        Route::prefix('email')->name('email.')->group(function () {
            // Email Configurations
            Route::prefix('configurations')->name('configurations.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'store'])->name('store');
                Route::get('/{configuration}', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'show'])->name('show');
                Route::get('/{configuration}/edit', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'edit'])->name('edit');
                Route::put('/{configuration}', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'update'])->name('update');
                Route::delete('/{configuration}', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'destroy'])->name('destroy');
                Route::post('/{configuration}/test', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'test'])->name('test');
                Route::put('/{configuration}/set-default', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'setDefault'])->name('set-default');
                Route::put('/{configuration}/toggle-active', [\App\Http\Controllers\Admin\EmailConfigurationController::class, 'toggleActive'])->name('toggle-active');
            });
        });

        // Email Management
        Route::prefix('emails')->name('emails.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\EmailController::class, 'index'])->name('index');
            Route::get('/get', [\App\Http\Controllers\Admin\EmailController::class, 'getEmails'])->name('get');
            Route::get('/compose', [\App\Http\Controllers\Admin\EmailController::class, 'compose'])->name('compose');
            Route::post('/send', [\App\Http\Controllers\Admin\EmailController::class, 'send'])->name('send');
            Route::get('/{email}', [\App\Http\Controllers\Admin\EmailController::class, 'show'])->name('show');
            Route::post('/{email}/read', [\App\Http\Controllers\Admin\EmailController::class, 'markAsRead'])->name('read');
            Route::post('/{email}/unread', [\App\Http\Controllers\Admin\EmailController::class, 'markAsUnread'])->name('unread');
            Route::post('/{email}/important', [\App\Http\Controllers\Admin\EmailController::class, 'markAsImportant'])->name('important');
            Route::post('/{email}/spam', [\App\Http\Controllers\Admin\EmailController::class, 'markAsSpam'])->name('spam');
            Route::post('/{email}/archive', [\App\Http\Controllers\Admin\EmailController::class, 'archive'])->name('archive');
            Route::delete('/{email}', [\App\Http\Controllers\Admin\EmailController::class, 'destroy'])->name('destroy');
            Route::get('/attachments/{attachment}/download', [\App\Http\Controllers\Admin\EmailController::class, 'downloadAttachment'])->name('attachments.download');
        });

        // Admin can access all dashboards
        Route::get('/developer', [\App\Http\Controllers\Developer\HomeController::class, 'index'])->name('developer');
        Route::get('/family', [\App\Http\Controllers\Family\HomeController::class, 'index'])->name('family');
    });

    // User approval routes
    Route::get('/users/{id}/approve', [App\Http\Controllers\Admin\UserApprovalController::class, 'approve'])->name('users.approve');
    Route::get('/users/{id}/reject', [App\Http\Controllers\Admin\UserApprovalController::class, 'reject'])->name('users.reject');
});

// Family Routes
Route::middleware(['auth', 'password.change.required'])->prefix('family')->name('family.')->group(function () {
    Route::middleware(['check.role:Family|Admin|Global Admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Family\HomeController::class, 'index'])->name('home');
        Route::get('/about', [\App\Http\Controllers\Family\AboutController::class, 'index'])->name('about');
        Route::get('/contact', [\App\Http\Controllers\Family\ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [\App\Http\Controllers\Family\ContactController::class, 'store'])->name('contact.store');

        // User Settings including Storage
        Route::get('/settings', [\App\Http\Controllers\Family\UserSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Family\UserSettingsController::class, 'update'])->name('settings.update');

        // User Storage Management
        Route::prefix('storage')->name('storage.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Family\StorageController::class, 'index'])->name('index');
            Route::get('/files', [\App\Http\Controllers\Family\StorageController::class, 'files'])->name('files');
            Route::get('/photos', [\App\Http\Controllers\Family\StorageController::class, 'photos'])->name('photos');
            Route::get('/galleries', [\App\Http\Controllers\Family\StorageController::class, 'galleries'])->name('galleries');
        });

        // Files and Folders
        Route::prefix('files')->name('files.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Family\FileController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Family\FileController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Family\FileController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Family\FileController::class, 'show'])->name('show');
            Route::get('/{id}/download', [\App\Http\Controllers\Family\FileController::class, 'download'])->name('download');
        });

        Route::prefix('folders')->name('folders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Family\FolderController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Family\FolderController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Family\FolderController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Family\FolderController::class, 'show'])->name('show');
        });

        // Galleries and Photos
        Route::prefix('galleries')->name('galleries.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Family\GalleryController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Family\GalleryController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Family\GalleryController::class, 'store'])->name('store');
            Route::get('/{slug}', [\App\Http\Controllers\Family\GalleryController::class, 'show'])->name('show');
            Route::post('/{slug}/photos', [\App\Http\Controllers\Family\GalleryController::class, 'uploadPhotos'])->name('photos.upload');
        });

        Route::prefix('photos')->name('photos.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Family\PhotoController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Family\PhotoController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Family\PhotoController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Family\PhotoController::class, 'update'])->name('update');
            Route::get('/{id}/download', [\App\Http\Controllers\Family\PhotoController::class, 'download'])->name('download');
            // Photo view route removed - now handled by modal carousel
        });

        // Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Family\ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [\App\Http\Controllers\Family\ProfileController::class, 'update'])->name('update');
            Route::get('/password', [\App\Http\Controllers\Family\ProfileController::class, 'editPassword'])->name('password');
            Route::put('/password', [\App\Http\Controllers\Family\ProfileController::class, 'updatePassword'])->name('password.update');
        });

        // Help Page
        Route::get('/help', [\App\Http\Controllers\Family\HelpController::class, 'index'])->name('help.index');
        Route::get('/help/{section}', [\App\Http\Controllers\Family\HelpController::class, 'show'])->name('help.show');
    });

});

// Password change routes for first login
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
    Route::put('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

// Apply password change required middleware to all protected routes
Route::middleware(['auth', 'password.change.required'])->group(function () {
    // Family, Admin, and Developer routes are already inside auth middleware
    // This ensures all authenticated routes require password change if needed
});

// Override Laravel's default register routes
Route::get('register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [CustomRegisterController::class, 'register']);

Route::get('/test-styles', function () {
    return view('test-styles');
});

Route::get('/test-roles', [App\Http\Controllers\TestController::class, 'index']);

// User storage route (for authenticated users outside family portal)
Route::middleware(['auth', 'password.change.required'])->get('/storage', [App\Http\Controllers\User\StorageController::class, 'index'])->name('user.storage');
