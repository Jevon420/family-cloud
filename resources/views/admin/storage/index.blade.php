@extends('admin.layouts.app')

@section('title', 'Storage Management')

@section('content')
<div class="container-fluid">
    <!-- Storage Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Storage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['total_storage_gb'], 2) }} GB</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Assigned Storage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['assigned_storage_gb'], 2) }} GB</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Used Storage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['total_used_storage_gb'], 2) }} GB</div>
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $statistics['storage_percentage_used'] }}%"></div>
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

    <!-- Storage Settings -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Storage Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.storage.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="total_storage_gb">Total Storage (GB)</label>
                            <input type="number" step="0.01" class="form-control" id="total_storage_gb" name="total_storage_gb" value="{{ $statistics['total_storage_gb'] }}" required>
                        </div>

                        <div class="form-group">
                            <label for="storage_percentage">Storage Percentage for Users (%)</label>
                            <input type="number" step="0.01" class="form-control" id="storage_percentage" name="storage_percentage" value="80" min="1" max="100" required>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="auto_detect_storage" name="auto_detect_storage" checked>
                                <label class="custom-control-label" for="auto_detect_storage">Auto-detect available storage</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Storage Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Users</small>
                        <div class="h5">{{ $statistics['user_count'] }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Available Storage</small>
                        <div class="h5">{{ number_format($statistics['available_storage_gb'], 2) }} GB</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Storage Usage</small>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ $statistics['storage_percentage_used'] }}%">{{ $statistics['storage_percentage_used'] }}%</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.storage.recalculate') }}">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block">Recalculate User Quotas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
