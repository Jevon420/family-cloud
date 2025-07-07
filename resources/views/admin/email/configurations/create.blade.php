@extends('admin.layouts.app')

@section('title', 'Create Email Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Create Email Configuration
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email.configurations.store') }}" method="POST">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Basic Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Configuration Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Email Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Configuration Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Both (Send & Receive)</option>
                                        <option value="outgoing" {{ old('type') == 'outgoing' ? 'selected' : '' }}>Outgoing Only</option>
                                        <option value="incoming" {{ old('type') == 'incoming' ? 'selected' : '' }}>Incoming Only</option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="from_name" class="form-label">Display Name</label>
                                    <input type="text" class="form-control @error('from_name') is-invalid @enderror"
                                           id="from_name" name="from_name" value="{{ old('from_name') }}">
                                    @error('from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Set as Default (for outgoing emails)
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SMTP Settings -->
                        <div class="row mb-4" id="smtpSettings">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">SMTP Settings (Outgoing)</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="smtp_host" class="form-label">SMTP Host</label>
                                    <input type="text" class="form-control @error('smtp_host') is-invalid @enderror"
                                           id="smtp_host" name="smtp_host" value="{{ old('smtp_host', 'smtp.hostinger.com') }}">
                                    @error('smtp_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="smtp_port" class="form-label">SMTP Port</label>
                                    <input type="number" class="form-control @error('smtp_port') is-invalid @enderror"
                                           id="smtp_port" name="smtp_port" value="{{ old('smtp_port', '465') }}">
                                    @error('smtp_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="smtp_encryption" class="form-label">SMTP Encryption</label>
                                    <select class="form-select @error('smtp_encryption') is-invalid @enderror" id="smtp_encryption" name="smtp_encryption">
                                        <option value="">None</option>
                                        <option value="ssl" {{ old('smtp_encryption', 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="tls" {{ old('smtp_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="starttls" {{ old('smtp_encryption') == 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                    </select>
                                    @error('smtp_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="smtp_username" class="form-label">SMTP Username</label>
                                    <input type="text" class="form-control @error('smtp_username') is-invalid @enderror"
                                           id="smtp_username" name="smtp_username" value="{{ old('smtp_username') }}"
                                           placeholder="Leave empty to use email address">
                                    @error('smtp_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- IMAP Settings -->
                        <div class="row mb-4" id="imapSettings">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">IMAP Settings (Incoming)</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="imap_host" class="form-label">IMAP Host</label>
                                    <input type="text" class="form-control @error('imap_host') is-invalid @enderror"
                                           id="imap_host" name="imap_host" value="{{ old('imap_host', 'imap.hostinger.com') }}">
                                    @error('imap_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="imap_port" class="form-label">IMAP Port</label>
                                    <input type="number" class="form-control @error('imap_port') is-invalid @enderror"
                                           id="imap_port" name="imap_port" value="{{ old('imap_port', '993') }}">
                                    @error('imap_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="imap_encryption" class="form-label">IMAP Encryption</label>
                                    <select class="form-select @error('imap_encryption') is-invalid @enderror" id="imap_encryption" name="imap_encryption">
                                        <option value="">None</option>
                                        <option value="ssl" {{ old('imap_encryption', 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="tls" {{ old('imap_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="starttls" {{ old('imap_encryption') == 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                    </select>
                                    @error('imap_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="imap_username" class="form-label">IMAP Username</label>
                                    <input type="text" class="form-control @error('imap_username') is-invalid @enderror"
                                           id="imap_username" name="imap_username" value="{{ old('imap_username') }}"
                                           placeholder="Leave empty to use email address">
                                    @error('imap_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- POP Settings -->
                        <div class="row mb-4" id="popSettings">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">POP3 Settings (Alternative Incoming)</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pop_host" class="form-label">POP3 Host</label>
                                    <input type="text" class="form-control @error('pop_host') is-invalid @enderror"
                                           id="pop_host" name="pop_host" value="{{ old('pop_host', 'pop.hostinger.com') }}">
                                    @error('pop_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="pop_port" class="form-label">POP3 Port</label>
                                    <input type="number" class="form-control @error('pop_port') is-invalid @enderror"
                                           id="pop_port" name="pop_port" value="{{ old('pop_port', '995') }}">
                                    @error('pop_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="pop_encryption" class="form-label">POP3 Encryption</label>
                                    <select class="form-select @error('pop_encryption') is-invalid @enderror" id="pop_encryption" name="pop_encryption">
                                        <option value="">None</option>
                                        <option value="ssl" {{ old('pop_encryption', 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="tls" {{ old('pop_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="starttls" {{ old('pop_encryption') == 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                    </select>
                                    @error('pop_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pop_username" class="form-label">POP3 Username</label>
                                    <input type="text" class="form-control @error('pop_username') is-invalid @enderror"
                                           id="pop_username" name="pop_username" value="{{ old('pop_username') }}"
                                           placeholder="Leave empty to use email address">
                                    @error('pop_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Signature -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Email Signature</h5>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="signature" class="form-label">Email Signature (HTML)</label>
                                    <textarea class="form-control @error('signature') is-invalid @enderror"
                                              id="signature" name="signature" rows="4">{{ old('signature') }}</textarea>
                                    @error('signature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">HTML is supported. This will be appended to outgoing emails.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.email.configurations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const smtpSettings = document.getElementById('smtpSettings');
    const imapSettings = document.getElementById('imapSettings');
    const popSettings = document.getElementById('popSettings');

    function toggleSections() {
        const type = typeSelect.value;

        if (type === 'outgoing') {
            smtpSettings.style.display = 'flex';
            imapSettings.style.display = 'none';
            popSettings.style.display = 'none';
        } else if (type === 'incoming') {
            smtpSettings.style.display = 'none';
            imapSettings.style.display = 'flex';
            popSettings.style.display = 'flex';
        } else if (type === 'both') {
            smtpSettings.style.display = 'flex';
            imapSettings.style.display = 'flex';
            popSettings.style.display = 'flex';
        } else {
            smtpSettings.style.display = 'none';
            imapSettings.style.display = 'none';
            popSettings.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleSections);
    toggleSections(); // Initial call
});
</script>
@endpush
