@extends('admin.layouts.app')

@section('title', 'Comprehensive Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Comprehensive Settings Management
                    </h4>
                    <div class="mt-3">
                        <p class="mb-1"><strong>Role:</strong> {{ auth()->user()->getRoleNames()->first() }}</p>
                        @php
                            $dbTimezone = $siteSettings->flatten()->where('key', 'timezone')->first()->value ?? 'UTC';
                        @endphp
                        <p class="mb-1"><strong>DB Timezone:</strong> {{ $dbTimezone }} ({{ now()->setTimezone($dbTimezone)->format('Y-m-d H:i:s') }})</p>
                        <p class="mb-1"><strong>App Timezone:</strong> {{ config('app.timezone') }} ({{ now()->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') }})</p>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                        @if($siteSettings->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="site-tab" data-bs-toggle="tab" data-bs-target="#site-settings" type="button" role="tab">
                                <i class="fas fa-globe me-1"></i>Site Settings
                            </button>
                        </li>
                        @endif

                        @if($systemConfigurations->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system-configurations" type="button" role="tab">
                                <i class="fas fa-server me-1"></i>System Config
                            </button>
                        </li>
                        @endif

                        @if($securitySettings->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-settings" type="button" role="tab">
                                <i class="fas fa-shield-alt me-1"></i>Security
                            </button>
                        </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Developer', 'Global Admin']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tools-tab" data-bs-toggle="tab" data-bs-target="#admin-tools" type="button" role="tab">
                                <i class="fas fa-tools me-1"></i>Admin Tools
                            </button>
                        </li>
                        @endif

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#system-info" type="button" role="tab">
                                <i class="fas fa-info-circle me-1"></i>System Info
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="settingsTabContent">

                        <!-- Site Settings Tab -->
                        @if($siteSettings->count() > 0)
                        <div class="tab-pane fade show active" id="site-settings" role="tabpanel">
                            <form id="siteSettingsForm">
                                @csrf
                                @foreach($siteSettings as $group => $settings)
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $group)) }} Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($settings as $setting)
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                                    @if($setting->description)
                                                    <i class="fas fa-info-circle text-muted ms-1" data-bs-toggle="tooltip" title="{{ $setting->description }}"></i>
                                                    @endif
                                                </label>

                                                @if($setting->type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="settings[{{ $setting->key }}][value]"
                                                           id="{{ $setting->key }}"
                                                           {{ $setting->value == 'true' ? 'checked' : '' }}
                                                           {{ auth()->user()->can('update', $setting) ? '' : 'disabled' }}>
                                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                                </div>
                                                @elseif($setting->type === 'number')
                                                <input type="number" class="form-control"
                                                       name="settings[{{ $setting->key }}][value]"
                                                       value="{{ $setting->value }}"
                                                       {{ auth()->user()->can('update', $setting) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                                @else
                                                <input type="text" class="form-control"
                                                       name="settings[{{ $setting->key }}][value]"
                                                       value="{{ $setting->value }}"
                                                       {{ auth()->user()->can('update', $setting) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($siteSettings->flatten()->filter(function($setting) { return auth()->user()->can('update', $setting); })->count() > 0)
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Save Site Settings
                                </button>
                                @endif
                            </form>
                        </div>
                        @endif

                        <!-- System Configurations Tab -->
                        @if($systemConfigurations->count() > 0)
                        <div class="tab-pane fade" id="system-configurations" role="tabpanel">
                            <form id="systemConfigForm">
                                @csrf
                                @foreach($systemConfigurations as $group => $configs)
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $group)) }} Configuration</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($configs as $config)
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    {{ ucfirst(str_replace('_', ' ', $config->key)) }}
                                                    @if($config->description)
                                                    <i class="fas fa-info-circle text-muted ms-1" data-bs-toggle="tooltip" title="{{ $config->description }}"></i>
                                                    @endif
                                                    @if($config->requires_restart)
                                                    <span class="badge bg-warning ms-1">Requires Restart</span>
                                                    @endif
                                                    @if($config->is_sensitive)
                                                    <span class="badge bg-danger ms-1">Sensitive</span>
                                                    @endif
                                                </label>

                                                @if($config->type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="settings[{{ $config->key }}][value]"
                                                           id="{{ $config->key }}"
                                                           {{ $config->value == 'true' ? 'checked' : '' }}
                                                           {{ auth()->user()->can('update', $config) ? '' : 'disabled' }}>
                                                    <input type="hidden" name="settings[{{ $config->key }}][key]" value="{{ $config->key }}">
                                                    <input type="hidden" name="settings[{{ $config->key }}][type]" value="{{ $config->type }}">
                                                </div>
                                                @elseif($config->type === 'password')
                                                <input type="password" class="form-control"
                                                       name="settings[{{ $config->key }}][value]"
                                                       placeholder="••••••••"
                                                       {{ auth()->user()->can('update', $config) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $config->key }}][key]" value="{{ $config->key }}">
                                                <input type="hidden" name="settings[{{ $config->key }}][type]" value="{{ $config->type }}">
                                                @elseif($config->type === 'number')
                                                <input type="number" class="form-control"
                                                       name="settings[{{ $config->key }}][value]"
                                                       value="{{ $config->value }}"
                                                       {{ auth()->user()->can('update', $config) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $config->key }}][key]" value="{{ $config->key }}">
                                                <input type="hidden" name="settings[{{ $config->key }}][type]" value="{{ $config->type }}">
                                                @else
                                                <input type="text" class="form-control"
                                                       name="settings[{{ $config->key }}][value]"
                                                       value="{{ $config->value }}"
                                                       {{ auth()->user()->can('update', $config) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $config->key }}][key]" value="{{ $config->key }}">
                                                <input type="hidden" name="settings[{{ $config->key }}][type]" value="{{ $config->type }}">
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($systemConfigurations->flatten()->filter(function($config) { return auth()->user()->can('update', $config); })->count() > 0)
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Save System Configuration
                                </button>
                                @endif
                            </form>
                        </div>
                        @endif

                        <!-- Security Settings Tab -->
                        @if($securitySettings->count() > 0)
                        <div class="tab-pane fade" id="security-settings" role="tabpanel">
                            <form id="securitySettingsForm">
                                @csrf
                                @foreach($securitySettings as $group => $settings)
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $group)) }} Security</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($settings as $setting)
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                                    @if($setting->description)
                                                    <i class="fas fa-info-circle text-muted ms-1" data-bs-toggle="tooltip" title="{{ $setting->description }}"></i>
                                                    @endif
                                                    @if($setting->is_critical)
                                                    <span class="badge bg-danger ms-1">Critical</span>
                                                    @endif
                                                </label>

                                                @if($setting->type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="settings[{{ $setting->key }}][value]"
                                                           id="{{ $setting->key }}"
                                                           {{ $setting->value == 'true' ? 'checked' : '' }}
                                                           {{ auth()->user()->can('update', $setting) ? '' : 'disabled' }}>
                                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                                </div>
                                                @elseif($setting->type === 'number')
                                                <input type="number" class="form-control"
                                                       name="settings[{{ $setting->key }}][value]"
                                                       value="{{ $setting->value }}"
                                                       {{ auth()->user()->can('update', $setting) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                                @else
                                                <input type="text" class="form-control"
                                                       name="settings[{{ $setting->key }}][value]"
                                                       value="{{ $setting->value }}"
                                                       {{ auth()->user()->can('update', $setting) ? '' : 'readonly' }}>
                                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($securitySettings->flatten()->filter(function($setting) { return auth()->user()->can('update', $setting); })->count() > 0)
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Save Security Settings
                                </button>
                                @endif
                            </form>
                        </div>
                        @endif

                        <!-- Admin Tools Tab -->
                        @if(auth()->user()->hasAnyRole(['Developer', 'Global Admin']))
                        <div class="tab-pane fade" id="admin-tools" role="tabpanel">
                            <div class="row">
                                <!-- Cache Management -->
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0"><i class="fas fa-memory me-1"></i>Cache Management</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Clear application caches to improve performance or apply configuration changes.</p>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-outline-info" onclick="clearCache('config')">
                                                    <i class="fas fa-cog me-1"></i>Clear Config Cache
                                                </button>
                                                <button class="btn btn-outline-info" onclick="clearCache('route')">
                                                    <i class="fas fa-route me-1"></i>Clear Route Cache
                                                </button>
                                                <button class="btn btn-outline-info" onclick="clearCache('view')">
                                                    <i class="fas fa-eye me-1"></i>Clear View Cache
                                                </button>
                                                <button class="btn btn-outline-info" onclick="clearCache('application')">
                                                    <i class="fas fa-database me-1"></i>Clear Application Cache
                                                </button>
                                                <button class="btn btn-warning" onclick="clearCache('all')">
                                                    <i class="fas fa-trash me-1"></i>Clear All Caches
                                                </button>
                                            </div>

                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <strong>Cache Status:</strong><br>
                                                    Config: {{ $cacheInfo['config_cached'] ? 'Cached' : 'Not Cached' }}<br>
                                                    Routes: {{ $cacheInfo['routes_cached'] ? 'Cached' : 'Not Cached' }}<br>
                                                    Views: {{ $cacheInfo['views_cached'] ? 'Cached' : 'Not Cached' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Log Management -->
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="mb-0"><i class="fas fa-file-alt me-1"></i>Log Management</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">View and manage application logs for debugging and monitoring.</p>

                                            <div class="mb-3">
                                                <label class="form-label">Log Type:</label>
                                                <select class="form-select" id="logType">
                                                    <option value="laravel">Laravel Log</option>
                                                    <option value="error">Error Log</option>
                                                </select>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button class="btn btn-outline-warning" onclick="viewLogs()">
                                                    <i class="fas fa-eye me-1"></i>View Latest Logs
                                                </button>
                                                @if(auth()->user()->hasRole('Developer'))
                                                <button class="btn btn-outline-secondary" onclick="downloadLogs()">
                                                    <i class="fas fa-download me-1"></i>Download Logs
                                                </button>
                                                @endif
                                            </div>

                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <strong>Available Logs:</strong><br>
                                                    @foreach($logInfo as $log)
                                                    {{ $log['name'] }} ({{ $log['formatted_size'] }})<br>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Log Viewer -->
                            <div class="card" id="logViewer" style="display: none;">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-terminal me-1"></i>Log Output</h5>
                                </div>
                                <div class="card-body">
                                    <pre id="logContent" class="bg-dark text-light p-3" style="max-height: 400px; overflow-y: auto;"></pre>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- System Information Tab -->
                        <div class="tab-pane fade" id="system-info" role="tabpanel">
                            <div class="row">
                                <!-- System Information -->
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0"><i class="fas fa-server me-1"></i>System Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="overflow-x-auto overflow-scroll">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>PHP Version:</strong></td>
                                                        <td>{{ $systemInfo['php_version'] }}</td>
                                                    </tr>
                                                <tr>
                                                    <td><strong>Laravel Version:</strong></td>
                                                    <td>{{ $systemInfo['laravel_version'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Server:</strong></td>
                                                    <td>{{ $systemInfo['server_software'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Database:</strong></td>
                                                    <td>{{ $systemInfo['database_version'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Memory Limit:</strong></td>
                                                    <td>{{ $systemInfo['memory_limit'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Max Execution Time:</strong></td>
                                                    <td>{{ $systemInfo['max_execution_time'] }}s</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Upload Max Size:</strong></td>
                                                    <td>{{ $systemInfo['upload_max_filesize'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Post Max Size:</strong></td>
                                                    <td>{{ $systemInfo['post_max_size'] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Storage Information -->
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-hdd me-1"></i>Storage Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="overflow-x-auto overflow-scroll">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Storage Used:</strong></td>
                                                        <td>{{ $systemInfo['storage_usage']['formatted'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Cache Driver:</strong></td>
                                                    <td>{{ $cacheInfo['driver'] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Site Settings Form
document.getElementById('siteSettingsForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const settings = {};

    // Convert FormData to proper format
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('settings[') && key.endsWith('][value]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[value\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].value = value;
        } else if (key.startsWith('settings[') && key.endsWith('][key]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[key\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].key = value;
        } else if (key.startsWith('settings[') && key.endsWith('][type]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[type\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].type = value;
        }
    }

    // Handle checkboxes
    document.querySelectorAll('#siteSettingsForm input[type="checkbox"]').forEach(checkbox => {
        const name = checkbox.name;
        if (name.includes('[value]')) {
            const settingKey = name.match(/settings\[(.*?)\]\[value\]/)[1];
            if (settings[settingKey]) {
                settings[settingKey].value = checkbox.checked ? 'true' : 'false';
            }
        }
    });

    fetch('{{ route("admin.settings.comprehensive.site.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            settings: Object.values(settings)
        })
    )
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', 'Error updating settings');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error updating settings');
        console.error('Error:', error);
    });
});

// System Configuration Form
document.getElementById('systemConfigForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const settings = {};

    // Convert FormData to proper format
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('settings[') && key.endsWith('][value]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[value\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].value = value;
        } else if (key.startsWith('settings[') && key.endsWith('][key]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[key\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].key = value;
        } else if (key.startsWith('settings[') && key.endsWith('][type]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[type\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].type = value;
        }
    }

    // Handle checkboxes
    document.querySelectorAll('#systemConfigForm input[type="checkbox"]').forEach(checkbox => {
        const name = checkbox.name;
        if (name.includes('[value]')) {
            const settingKey = name.match(/settings\[(.*?)\]\[value\]/)[1];
            if (settings[settingKey]) {
                settings[settingKey].value = checkbox.checked ? 'true' : 'false';
            }
        }
    });

    fetch('{{ route("admin.settings.comprehensive.system.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            settings: Object.values(settings)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            if (data.requires_restart) {
                showAlert('warning', 'Some changes require application restart to take effect.');
            }
        } else {
            showAlert('danger', 'Error updating settings');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error updating settings');
        console.error('Error:', error);
    });
});

// Security Settings Form
document.getElementById('securitySettingsForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const settings = {};

    // Convert FormData to proper format
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('settings[') && key.endsWith('][value]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[value\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].value = value;
        } else if (key.startsWith('settings[') && key.endsWith('][key]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[key\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].key = value;
        } else if (key.startsWith('settings[') && key.endsWith('][type]')) {
            const settingKey = key.match(/settings\[(.*?)\]\[type\]/)[1];
            if (!settings[settingKey]) settings[settingKey] = {};
            settings[settingKey].type = value;
        }
    }

    // Handle checkboxes
    document.querySelectorAll('#securitySettingsForm input[type="checkbox"]').forEach(checkbox => {
        const name = checkbox.name;
        if (name.includes('[value]')) {
            const settingKey = name.match(/settings\[(.*?)\]\[value\]/)[1];
            if (settings[settingKey]) {
                settings[settingKey].value = checkbox.checked ? 'true' : 'false';
            }
        }
    });

    fetch('{{ route("admin.settings.comprehensive.security.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            settings: Object.values(settings)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', 'Error updating settings');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error updating settings');
        console.error('Error:', error);
    });
});

// Cache management functions
function clearCache(type) {
    if (!confirm(`Are you sure you want to clear ${type} cache?`)) {
        return;
    }

    fetch('{{ route("admin.settings.comprehensive.cache.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.error || 'Error clearing cache');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error clearing cache');
        console.error('Error:', error);
    });
}

// Log management functions
function viewLogs() {
    const logType = document.getElementById('logType').value;

    fetch(`{{ route("admin.settings.comprehensive.logs.view") }}?type=${logType}&lines=100`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('logContent').textContent = data.logs.join('');
            document.getElementById('logViewer').style.display = 'block';
        } else {
            showAlert('danger', data.error || 'Error loading logs');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error loading logs');
        console.error('Error:', error);
    });
}

function downloadLogs() {
    const logType = document.getElementById('logType').value;
    window.open(`{{ route("admin.settings.comprehensive.logs.download") }}?type=${logType}`, '_blank');
}

// Alert function
function showAlert(type, message) {
    const alertContainer = document.querySelector('.card-body') || document.body;
    const alertId = 'alert-' + Date.now();

    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" id="${alertId}">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    alertContainer.insertAdjacentHTML('afterbegin', alertHtml);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            const alert = new bootstrap.Alert(alertElement);
            alert.close();
        }
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.nav-tabs .nav-link {
    color: #495057;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.badge {
    font-size: 0.75em;
}

.card {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

pre {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.4;
}

.table td {
    padding: 0.5rem;
    border-top: 1px solid #dee2e6;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.text-muted {
    color: #6c757d !important;
}
</style>
@endpush
