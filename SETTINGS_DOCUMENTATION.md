# Family Cloud - Comprehensive Settings System

This document explains the comprehensive settings system implemented in the Family Cloud application.

## Overview

The settings system provides role-based access to three types of settings:

1. **Site Settings** - General website configuration
2. **System Configurations** - Technical system settings
3. **Security Settings** - Security-related configurations

## Access Levels

### Developer Role
- **Full access** to all settings types
- Can create, read, update, and delete all settings
- Can access sensitive configuration values
- Can manage system configurations that require restart

### Global Admin Role
- **Full access** to Site Settings
- **Limited access** to System Configurations (non-sensitive only)
- **Limited access** to Security Settings (non-critical only)
- Can clear caches and view logs
- Cannot access developer-only settings

### Admin Role
- **Limited access** to Site Settings (admin level only)
- **Read-only access** to public site settings
- Cannot access System Configurations or Security Settings
- Cannot access administrative tools

## Settings Types

### Site Settings
Located in `site_settings` table with access levels:
- `general` - Site name, description, timezone
- `branding` - Logo, themes, visual settings
- `user_management` - Registration settings
- `storage` - File upload limits, allowed types
- `notifications` - Email and notification settings
- `backup` - Backup configuration

### System Configurations  
Located in `system_configurations` table with groups:
- `cache` - Cache driver and TTL settings
- `queue` - Queue driver and timeout settings
- `mail` - SMTP configuration
- `storage` - File system and cloud storage settings
- `logging` - Log levels and retention

### Security Settings
Located in `security_settings` table with groups:
- `authentication` - Password policies, 2FA, session settings
- `authorization` - Role hierarchy, permission caching
- `encryption` - App keys, cookie encryption
- `security` - CSRF protection, secure headers

## Usage Examples

### Using the Settings Service

```php
// Inject the service
use App\Services\SettingsService;

public function __construct(SettingsService $settings)
{
    $this->settings = $settings;
}

// Get site settings
$siteName = $this->settings->getSiteSetting('site_name', 'Default Name');
$maxUpload = $this->settings->getMaxFileUploadSize(); // Helper method

// Set site settings
$this->settings->setSiteSetting('site_name', 'New Site Name');

// Get system configurations
$cacheDriver = $this->settings->getSystemConfiguration('cache_driver', 'file');

// Get security settings
$sessionLifetime = $this->settings->getSecuritySetting('session_lifetime', 120);
```

### Using the Settings Facade

```php
use Settings;

// Get settings
$siteName = Settings::getSiteSetting('site_name');
$isMaintenanceMode = Settings::isMaintenanceMode();
$allowedTypes = Settings::getAllowedFileTypes();

// Set settings
Settings::setSiteSetting('maintenance_mode', true, 'boolean');
```

## Database Schema

### Site Settings Table
```sql
- id (primary key)
- key (unique string)
- value (text)
- type (string: string, boolean, number, json)
- group (string: general, branding, etc.)
- description (text)
- validation_rules (json)
- access_level (string: developer, global_admin, admin)
- is_public (boolean)
- created_by, updated_by, deleted_by (foreign keys)
- timestamps, soft deletes
```

### System Configurations Table
```sql
- id (primary key)
- key (unique string)
- value (text)
- type (string: string, boolean, number, json, password)
- group (string: cache, queue, mail, etc.)
- description (text)
- is_sensitive (boolean)
- requires_restart (boolean)
- validation_rules (json)
- access_level (string: developer, global_admin, admin)
- created_by, updated_by, deleted_by (foreign keys)
- timestamps, soft deletes
```

### Security Settings Table
```sql
- id (primary key)
- key (unique string)
- value (text)
- type (string: string, boolean, number, json)
- group (string: authentication, authorization, etc.)
- description (text)
- is_critical (boolean)
- validation_rules (json)
- access_level (string: developer, global_admin)
- created_by, updated_by, deleted_by (foreign keys)
- timestamps, soft deletes
```

## Admin Interface

Access the comprehensive settings through:
- URL: `/admin/settings/comprehensive`
- Navigation: Admin sidebar → "Advanced Settings"

The interface provides:
- **Tabbed interface** for different setting types
- **Role-based visibility** of settings
- **Real-time form validation**
- **Cache management tools** (Developer/Global Admin only)
- **Log viewing tools** (Developer/Global Admin only)
- **System information display**

## Cache Management

Settings are automatically cached for 1 hour. Cache keys:
- Site: `settings:site:{key}`
- System: `settings:system:{key}`
- Security: `settings:security:{key}`
- User: `settings:user:{user_id}:{key}`

Clear caches via:
- Admin interface cache tools
- `Settings::clearAllSettingsCache()`
- Artisan commands (`php artisan cache:clear`)

## Security Features

1. **Role-based access control** via policies
2. **Sensitive value masking** for non-developers
3. **Critical setting protection** for security settings
4. **Audit trails** for all setting changes
5. **CSRF protection** on all update endpoints
6. **Input validation** based on setting type

## API Endpoints

```php
// View settings (GET)
/admin/settings/comprehensive

// Update site settings (POST)
/admin/settings/comprehensive/site

// Update system configurations (POST) 
/admin/settings/comprehensive/system

// Update security settings (POST)
/admin/settings/comprehensive/security

// Clear cache (POST)
/admin/settings/comprehensive/cache/clear

// View logs (GET)
/admin/settings/comprehensive/logs/view

// Download logs (GET)
/admin/settings/comprehensive/logs/download
```

## Extending the System

### Adding New Settings

1. **Create migration** to add new setting records
2. **Update seeder** with default values
3. **Add helper methods** to SettingsService if needed
4. **Update policies** if access control needs modification

### Adding New Setting Types

1. **Update validation** in controllers
2. **Update type casting** in SettingsService
3. **Update frontend** form handling in blade template

## Maintenance

### Regular Tasks
- Monitor setting changes via audit logs
- Review and update default values
- Clear caches after system updates
- Backup settings before major changes

### Troubleshooting
- Check logs via admin interface
- Verify role assignments for access issues
- Clear caches if settings not updating
- Check policies for permission problems

## Best Practices

1. **Always use the service/facade** instead of direct model access
2. **Set appropriate access levels** for new settings
3. **Use descriptive keys** following snake_case convention
4. **Group related settings** logically
5. **Provide descriptions** for all settings
6. **Cache frequently accessed** settings
7. **Validate input** based on setting type
8. **Monitor sensitive settings** for unauthorized changes
