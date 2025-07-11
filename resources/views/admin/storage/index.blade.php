@extends('admin.layouts.app')

@section('title', 'Storage Management')

@section('content')
<div class="container-fluid">
    <!-- Wasabi Storage Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Wasabi Total Storage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['wasabi_total_storage_gb'], 2) }} GB</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cloud fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Allocated to FamilyCloud</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['wasabi_allocated_gb'], 2) }} GB</div>
                            <div class="text-xs text-gray-600">({{ $statistics['wasabi_allocation_percentage'] }}%)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Wasabi Used Storage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['wasabi_used_gb'], 2) }} GB</div>
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $statistics['wasabi_usage_percentage'] }}%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Per User Quota</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['per_user_quota_gb'], 2) }} GB</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Local Hosting Storage Overview -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Local Hosting Storage Overview</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Storage Used by Site:</strong> {{ number_format($statistics['local_storage_used_gb'], 2) }} GB</p>
                    <p><strong>Available Local Storage:</strong> {{ number_format($statistics['local_storage_available_gb'], 2) }} GB</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Site Files Count:</strong> {{ number_format($statistics['local_files_count']) }}</p>
                    <p><strong>Profile Images:</strong> {{ number_format($statistics['local_profile_images_count']) }}</p>
                </div>
            </div>
            <small class="text-muted">This includes site assets, logs, cache, profile images, and other hosting-related data.</small>
        </div>
    </div>

    <!-- Wasabi Storage Settings -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Wasabi Storage Settings</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.storage.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="wasabi_total_storage_gb">Total Wasabi Storage (GB)</label>
                            <input type="number" step="0.01" class="form-control" id="wasabi_total_storage_gb" name="wasabi_total_storage_gb" value="{{ $statistics['wasabi_total_storage_gb'] }}" required>
                            <small class="text-muted">Total storage available in your Wasabi account</small>
                        </div>

                        <div class="form-group">
                            <label for="wasabi_allocation_percentage">Allocation for FamilyCloud (%)</label>
                            <input type="number" step="0.01" class="form-control" id="wasabi_allocation_percentage" name="wasabi_allocation_percentage" value="{{ $statistics['wasabi_allocation_percentage'] }}" min="1" max="100" required>
                            <small class="text-muted">Percentage of total Wasabi storage to allocate to FamilyCloud users</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="signed_url_short_expiration">Short-Term Signed URL Expiration (Minutes)</label>
                            <input type="number" step="1" class="form-control" id="signed_url_short_expiration" name="signed_url_short_expiration" value="{{ $settings['signed_url_short_expiration'] }}" required>
                            <small class="text-muted">For private/temporary access</small>
                        </div>

                        <div class="form-group">
                            <label for="signed_url_long_expiration_years">Long-Term Signed URL Expiration (Years)</label>
                            <input type="number" step="1" class="form-control" id="signed_url_long_expiration_years" name="signed_url_long_expiration_years" value="{{ ceil($settings['signed_url_long_expiration'] / 525600) }}" required>
                            <small class="text-muted">For public/shared content</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Wasabi Settings</button>
            </form>
        </div>
    </div>

    <!-- User Storage Statistics -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Storage Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Users</small>
                        <div class="h5">{{ $statistics['user_count'] }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">User Files</small>
                        <div class="h5">{{ number_format($statistics['user_files_count']) }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">User Photos</small>
                        <div class="h5">{{ number_format($statistics['user_photos_count']) }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">User Galleries</small>
                        <div class="h5">{{ number_format($statistics['user_galleries_count']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Storage Management</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Wasabi Available Storage</small>
                        <div class="h5">{{ number_format($statistics['wasabi_available_gb'], 2) }} GB</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Wasabi Usage</small>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ $statistics['wasabi_usage_percentage'] }}%">{{ number_format($statistics['wasabi_usage_percentage'], 1) }}%</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.storage.recalculate') }}">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block">Recalculate User Quotas</button>
                        <p class="text-muted small mt-2">
                            This will recalculate and equally distribute Wasabi storage among all users based on current settings.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
