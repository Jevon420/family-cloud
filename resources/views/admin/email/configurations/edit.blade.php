@extends('admin.layouts.app')

@section('title', 'Edit Email Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Email Configuration
                    </h4>
                </div>
                <div class="card-body">
                    <form id="editForm" action="{{ route('admin.email.configurations.update', $configuration) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $configuration->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $configuration->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="incoming" {{ $configuration->type === 'incoming' ? 'selected' : '' }}>Incoming</option>
                                <option value="outgoing" {{ $configuration->type === 'outgoing' ? 'selected' : '' }}>Outgoing</option>
                                <option value="both" {{ $configuration->type === 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Active</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" {{ $configuration->is_active ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$configuration->is_active ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </span>
                        </div>

                        <div class="mb-3">
                            <label for="smtp_host" class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="{{ $configuration->smtp_host }}">
                        </div>

                        <div class="mb-3">
                            <label for="smtp_port" class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" id="smtp_port" name="smtp_port" value="{{ $configuration->smtp_port }}">
                        </div>

                        <div class="mb-3">
                            <label for="smtp_encryption" class="form-label">SMTP Encryption</label>
                            <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                <option value="ssl" {{ $configuration->smtp_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ $configuration->smtp_encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="starttls" {{ $configuration->smtp_encryption === 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="imap_host" class="form-label">IMAP Host</label>
                            <input type="text" class="form-control" id="imap_host" name="imap_host" value="{{ $configuration->imap_host }}">
                        </div>

                        <div class="mb-3">
                            <label for="imap_port" class="form-label">IMAP Port</label>
                            <input type="number" class="form-control" id="imap_port" name="imap_port" value="{{ $configuration->imap_port }}">
                        </div>

                        <div class="mb-3">
                            <label for="imap_encryption" class="form-label">IMAP Encryption</label>
                            <select class="form-select" id="imap_encryption" name="imap_encryption">
                                <option value="ssl" {{ $configuration->imap_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ $configuration->imap_encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="starttls" {{ $configuration->imap_encryption === 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="pop_host" class="form-label">POP Host</label>
                            <input type="text" class="form-control" id="pop_host" name="pop_host" value="{{ $configuration->pop_host }}">
                        </div>

                        <div class="mb-3">
                            <label for="pop_port" class="form-label">POP Port</label>
                            <input type="number" class="form-control" id="pop_port" name="pop_port" value="{{ $configuration->pop_port }}">
                        </div>

                        <div class="mb-3">
                            <label for="pop_encryption" class="form-label">POP Encryption</label>
                            <select class="form-select" id="pop_encryption" name="pop_encryption">
                                <option value="ssl" {{ $configuration->pop_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ $configuration->pop_encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="starttls" {{ $configuration->pop_encryption === 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="from_name" class="form-label">From Name</label>
                            <input type="text" class="form-control" id="from_name" name="from_name" value="{{ $configuration->from_name }}">
                        </div>

                        <div class="mb-3">
                            <label for="signature" class="form-label">Signature</label>
                            <textarea class="form-control" id="signature" name="signature" rows="3">{{ $configuration->signature }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning">Update Configuration</button>
                    </form>

                    <div id="successMessage" class="alert alert-success mt-3 d-none">
                        Configuration updated successfully.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(function(element) {
            element.remove();
        });

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('successMessage').classList.remove('d-none');
                document.getElementById('successMessage').textContent = data.message;
            } else {
                alert('Update failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    const errorMessages = errors[field];
                    const input = form.querySelector(`[name="${field}"]`);
                    const errorElement = document.createElement('div');
                    errorElement.classList.add('error-message', 'text-danger');
                    errorElement.innerHTML = errorMessages.join('<br>');
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                }
            } else {
                alert('Update failed: ' + error.message);
            }
        });
    });

    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('passwordToggleIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endpush
