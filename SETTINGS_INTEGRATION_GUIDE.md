# Settings Integration Examples

## Automatic Site-Wide Application

Your settings are now automatically applied site-wide through:

### 1. **Middleware Integration**
```php
// In web routes - automatically applied via ApplyGlobalSettings middleware
Route::post('/upload', [FileController::class, 'upload'])
    ->middleware(['validate.file.upload']); // Validates against dynamic file settings

Route::get('/register', [RegisterController::class, 'show'])
    ->middleware(['check.registration']); // Blocks if registration disabled
```

### 2. **View Integration**
All views now have access to settings automatically:
```blade
<!-- In any Blade template -->
<title>{{ $siteName }}</title>
<meta name="description" content="{{ $siteDescription }}">
<img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}">

<!-- File upload limits -->
<p>Maximum file size: {{ $maxFileUploadSize }}MB</p>
<p>Allowed types: {{ implode(', ', $allowedFileTypes) }}</p>

<!-- Conditional features -->
@if($registrationEnabled)
    <a href="{{ route('register') }}">Register</a>
@endif

@if($isMaintenanceMode && auth()->user()?->hasRole('Admin'))
    <div class="alert alert-warning">Site is in maintenance mode</div>
@endif
```

### 3. **Controller Usage**
```php
use Settings; // Use the facade

class FileController extends Controller
{
    public function upload(Request $request)
    {
        // Settings are automatically validated via middleware
        // But you can also access them directly:
        
        $maxSize = Settings::getMaxFileUploadSize();
        $allowedTypes = Settings::getAllowedFileTypes();
        
        // Your upload logic here
    }
    
    public function index()
    {
        // Check user storage quota
        $maxStorage = Settings::getMaxStoragePerUser();
        $userUsedStorage = $this->calculateUserStorage(auth()->id());
        
        return view('files.index', compact('maxStorage', 'userUsedStorage'));
    }
}
```

### 4. **Dynamic Configuration**
Settings automatically update:
- **App timezone** from site settings
- **Mail configuration** from system settings  
- **Session lifetime** from security settings
- **Cache driver** from system settings

### 5. **Maintenance Mode**
- Automatically shows maintenance page to non-admin users
- Admins can still access the site
- Toggleable from settings interface

## Manual Integration in Specific Areas

### Registration Form (example)
```php
// In your AuthController or wherever registration is handled
public function showRegistrationForm()
{
    if (!Settings::isRegistrationEnabled()) {
        return redirect()->route('login')
            ->withErrors(['registration' => 'Registration is currently disabled.']);
    }
    
    return view('auth.register');
}
```

### File Upload Validation (custom)
```php
public function validateFile($file)
{
    $maxSize = Settings::getMaxFileUploadSize() * 1024 * 1024;
    $allowedTypes = Settings::getAllowedFileTypes();
    
    if ($file->getSize() > $maxSize) {
        throw new ValidationException('File too large');
    }
    
    if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
        throw new ValidationException('File type not allowed');
    }
}
```

### Email Templates
```php
public function sendWelcomeEmail($user)
{
    $siteName = Settings::getSiteSetting('site_name');
    $siteEmail = Settings::getSiteSetting('site_email');
    
    Mail::to($user)->send(new WelcomeEmail($siteName, $siteEmail));
}
```

## Commands for Management

```bash
# Apply all settings changes
php artisan settings:apply --cache-clear

# Test settings system
php artisan settings:test

# Clear only settings cache
Settings::clearAllSettingsCache();
```

## Key Benefits

✅ **Automatic Application** - Most settings work without code changes
✅ **Role-Based Management** - Different access levels for different users
✅ **Live Updates** - Changes apply immediately (with cache clear)
✅ **Graceful Fallbacks** - App works even if settings fail to load
✅ **Type Safety** - Boolean, number, string types handled correctly
✅ **Caching** - Settings cached for performance
✅ **Middleware Protection** - File uploads, registration, maintenance mode
✅ **View Integration** - All templates have access to settings
✅ **Database Driven** - No need to modify config files
