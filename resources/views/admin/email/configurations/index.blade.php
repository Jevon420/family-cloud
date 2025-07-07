@extends('admin.layouts.app')

@section('title', 'Email Configurations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Email Configurations
                    </h4>
                    <div>
                        <a href="{{ route('admin.email.configurations.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Add Configuration
                        </a>
                        <a href="{{ route('admin.emails.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-envelope me-1"></i>View Emails
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Default</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configurations as $config)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong>{{ $config->name }}</strong>
                                                @if($config->from_name)
                                                <br><small class="text-muted">{{ $config->from_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $config->email }}</code>
                                    </td>
                                    <td>
                                        @if($config->type === 'both')
                                        <span class="badge bg-success">Both</span>
                                        @elseif($config->type === 'incoming')
                                        <span class="badge bg-info">Incoming</span>
                                        @elseif($config->type === 'outgoing')
                                        <span class="badge bg-warning">Outgoing</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($config->is_active)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($config->is_default)
                                        <span class="badge bg-primary">Default</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $config->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.email.configurations.show', $config) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.email.configurations.edit', $config) }}" class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info" onclick="testConfiguration({{ $config->id }})">
                                                <i class="fas fa-vial"></i>
                                            </button>
                                            @if($config->canSendEmail() && !$config->is_default)
                                            <form action="{{ route('admin.email.configurations.set-default', $config) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-success">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.email.configurations.toggle-active', $config) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-{{ $config->is_active ? 'warning' : 'success' }}">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.email.configurations.destroy', $config) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $configurations->links() }}
                    </div>

                    <!-- Mobile/Table Tile View -->
                    <div class="d-block d-md-none">
                        <div class="row">
                            @foreach($configurations as $config)
                            <div class="col-12 mb-3">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $config->name }}</h5>
                                        <p class="card-text">
                                            <strong>Email:</strong> <code>{{ $config->email }}</code><br>
                                            <strong>Type:</strong> {{ ucfirst($config->type) }}<br>
                                            <strong>Status:</strong> {{ $config->is_active ? 'Active' : 'Inactive' }}<br>
                                            <strong>Default:</strong> {{ $config->is_default ? 'Yes' : 'No' }}<br>
                                            <strong>Created:</strong> {{ $config->created_at->format('M d, Y') }}
                                        </p>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.email.configurations.show', $config) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.email.configurations.edit', $config) }}" class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info" onclick="testConfiguration({{ $config->id }})">
                                                <i class="fas fa-vial"></i>
                                            </button>
                                            @if($config->canSendEmail() && !$config->is_default)
                                            <form action="{{ route('admin.email.configurations.set-default', $config) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-success">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.email.configurations.toggle-active', $config) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-{{ $config->is_active ? 'warning' : 'success' }}">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.email.configurations.destroy', $config) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- End Mobile/Table Tile View -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testModalLabel">Test Email Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="testForm">
                    <div class="mb-3">
                        <label for="testEmail" class="form-label">Test Email Address</label>
                        <input type="email" class="form-control" id="testEmail" required>
                    </div>
                    <div id="testResult"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="runTest()">Run Test</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTestConfigId = null;

function testConfiguration(configId) {
    currentTestConfigId = configId;
    document.getElementById('testResult').innerHTML = '';
    new bootstrap.Modal(document.getElementById('testModal')).show();
}

function runTest() {
    const testEmail = document.getElementById('testEmail').value;
    const resultDiv = document.getElementById('testResult');

    if (!testEmail) {
        resultDiv.innerHTML = '<div class="alert alert-danger">Please enter a test email address</div>';
        return;
    }

    resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Testing...</div>';

    fetch(`{{ route('admin.email.configurations.test', '') }}/${currentTestConfigId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            test_email: testEmail
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else {
            resultDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div class="alert alert-danger">Test failed: Network error</div>';
    });
}
</script>
@endpush
