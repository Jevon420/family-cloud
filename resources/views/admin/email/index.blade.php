@extends('admin.layouts.app')

@section('title', 'Email Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        Email Management
                    </h4>
                    <div>
                        <a href="{{ route('admin.emails.compose') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Compose
                        </a>
                        <a href="{{ route('admin.email.configurations.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-cogs me-1"></i>Configurations
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="configurationFilter" name="configuration_id">
                                <option value="">All Configurations</option>
                                @foreach($configurations as $config)
                                <option value="{{ $config->id }}" {{ request('configuration_id') == $config->id ? 'selected' : '' }}>
                                    {{ $config->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="typeFilter" name="type">
                                <option value="">All Types</option>
                                <option value="received" {{ request('type') == 'received' ? 'selected' : '' }}>Received</option>
                                <option value="sent" {{ request('type') == 'sent' ? 'selected' : '' }}>Sent</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter" name="status">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search emails..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-primary" id="refreshBtn">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email List -->
                    <div id="emailList">
                        @include('admin.email.partials.email-list', ['emails' => $emails])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Email Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="emailModalBody">
                <!-- Email content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let debounceTimer;

    // Filter change handlers
    document.getElementById('configurationFilter').addEventListener('change', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 500);
    });
    document.getElementById('refreshBtn').addEventListener('click', applyFilters);

    function applyFilters() {
        const params = new URLSearchParams();

        const configId = document.getElementById('configurationFilter').value;
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchInput').value;

        if (configId) params.append('configuration_id', configId);
        if (type) params.append('type', type);
        if (status) params.append('status', status);
        if (search) params.append('search', search);

        fetch(`{{ route('admin.emails.get') }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('emailList').innerHTML = renderEmailList(data);
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Error loading emails');
            });
    }

    function renderEmailList(emails) {
        if (emails.data.length === 0) {
            return '<div class="text-center py-5"><p class="text-muted">No emails found</p></div>';
        }

        let html = '<div class="table-responsive"><table class="table table-hover">';
        html += '<thead><tr><th>From</th><th>Subject</th><th>Configuration</th><th>Type</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead><tbody>';

        emails.data.forEach(email => {
            const statusBadge = getStatusBadge(email.status);
            const typeBadge = getTypeBadge(email.type);
            const date = new Date(email.sent_at).toLocaleDateString();

            html += `
                <tr class="${email.status === 'unread' ? 'fw-bold' : ''}">
                    <td>
                        <div class="d-flex align-items-center">
                            ${email.is_important ? '<i class="fas fa-star text-warning me-1"></i>' : ''}
                            <div>
                                <div>${email.from_name || email.from_email}</div>
                                <small class="text-muted">${email.from_email}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            ${email.subject}
                            ${email.has_attachments ? '<i class="fas fa-paperclip text-muted ms-1"></i>' : ''}
                        </div>
                        <small class="text-muted">${email.preview_text || ''}</small>
                    </td>
                    <td><span class="badge bg-secondary">${email.email_configuration.name}</span></td>
                    <td>${typeBadge}</td>
                    <td>${statusBadge}</td>
                    <td>${date}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="viewEmail(${email.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-warning" onclick="markAsImportant(${email.id})">
                                <i class="fas fa-star"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteEmail(${email.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';

        // Add pagination
        if (emails.links) {
            html += '<div class="d-flex justify-content-center">' + emails.links + '</div>';
        }

        return html;
    }

    function getStatusBadge(status) {
        const badges = {
            'unread': '<span class="badge bg-primary">Unread</span>',
            'read': '<span class="badge bg-success">Read</span>',
            'archived': '<span class="badge bg-secondary">Archived</span>',
        };
        return badges[status] || status;
    }

    function getTypeBadge(type) {
        const badges = {
            'received': '<span class="badge bg-info">Received</span>',
            'sent': '<span class="badge bg-success">Sent</span>',
        };
        return badges[type] || type;
    }

    // Email actions
    window.viewEmail = function(emailId) {
        fetch(`{{ route('admin.emails.show', '') }}/${emailId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('emailModalBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('emailModal')).show();
            });
    };

    window.markAsImportant = function(emailId) {
        fetch(`{{ route('admin.emails.important', '') }}/${emailId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                applyFilters();
                showAlert('success', 'Email marked as important');
            }
        });
    };

    window.deleteEmail = function(emailId) {
        if (confirm('Are you sure you want to delete this email?')) {
            fetch(`{{ route('admin.emails.destroy', '') }}/${emailId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    applyFilters();
                    showAlert('success', 'Email deleted successfully');
                }
            });
        }
    };
});

function showAlert(type, message) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.card-body').prepend(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}
</script>
@endpush
