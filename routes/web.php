<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\AboutController as FrontendAboutController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\Frontend\PhotoController as FrontendPhotoController;
use App\Http\Controllers\Frontend\FileController as FrontendFileController;
use App\Http\Controllers\Frontend\FolderController as FrontendFolderController;

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

// Auth Required Routes
Route::middleware(['auth'])->group(function () {
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
Route::middleware(['auth'])->prefix('shared')->name('shared.')->group(function () {
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
Route::middleware(['auth'])->prefix('developer')->name('developer.')->group(function () {
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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['check.role:Admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

        // Admin Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
            Route::get('/users', [\App\Http\Controllers\Admin\SettingsController::class, 'users'])->name('users');
            Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\SettingsController::class, 'updateUserRole'])->name('users.role');
            Route::get('/system', [\App\Http\Controllers\Admin\SettingsController::class, 'system'])->name('system');
        });

        // Admin can access all dashboards
        Route::get('/developer', [\App\Http\Controllers\Developer\HomeController::class, 'index'])->name('developer');
        Route::get('/family', [\App\Http\Controllers\Family\HomeController::class, 'index'])->name('family');
    });
});

// Family Routes
Route::middleware(['auth'])->prefix('family')->name('family.')->group(function () {
    Route::middleware(['check.role:Family|Admin|Global Admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Family\HomeController::class, 'index'])->name('home');
        Route::get('/about', [\App\Http\Controllers\Family\AboutController::class, 'index'])->name('about');
        Route::get('/contact', [\App\Http\Controllers\Family\ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [\App\Http\Controllers\Family\ContactController::class, 'store'])->name('contact.store');
    });
});

Route::get('/test-styles', function () {
    return view('test-styles');
});

Route::get('/test-roles', [App\Http\Controllers\TestController::class, 'index']);
