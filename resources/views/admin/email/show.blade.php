@extends('admin.layouts.app')

@section('title', 'Email Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        Email Details
                    </h4>
                    <div>
                        <a href="{{ route('admin.emails.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i>Back to Emails
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Email Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">{{ $email->subject }}</h5>
                                        <div class="mb-2">
                                            <strong>From:</strong>
                                            {{ $email->from_name ? $email->from_name . ' <' . $email->from_email . '>' : $email->from_email }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>To:</strong> {{ $email->to_emails_string }}
                                        </div>
                                        @if($email->cc_emails)
                                        <div class="mb-2">
                                            <strong>CC:</strong> {{ $email->cc_emails_string }}
                                        </div>
                                        @endif
                                        @if($email->bcc_emails)
                                        <div class="mb-2">
                                            <strong>BCC:</strong> {{ $email->bcc_emails_string }}
                                        </div>
                                        @endif
                                        <div class="mb-2">
                                            <strong>Configuration:</strong>
                                            <span class="badge bg-secondary">{{ $email->emailConfiguration->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="mb-2">
                                            @if($email->type === 'received')
                                            <span class="badge bg-info">Received</span>
                                            @elseif($email->type === 'sent')
                                            <span class="badge bg-success">Sent</span>
                                            @endif

                                            @if($email->status === 'unread')
                                            <span class="badge bg-primary">Unread</span>
                                            @elseif($email->status === 'read')
                                            <span class="badge bg-success">Read</span>
                                            @elseif($email->status === 'archived')
                                            <span class="badge bg-secondary">Archived</span>
                                            @endif

                                            @if($email->is_important)
                                            <span class="badge bg-warning">Important</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">{{ $email->sent_at->format('M d, Y g:i A') }}</small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-warning" onclick="markAsImportant({{ $email->id }})">
                                                <i class="fas fa-star"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" onclick="archive({{ $email->id }})">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                            <form action="{{ route('admin.emails.destroy', $email) }}" method="POST" class="d-inline">
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
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($email->attachments->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Attachments ({{ $email->attachments->count() }})</h6>
                            <div class="border rounded p-3">
                                @foreach($email->attachments as $attachment)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        @if($attachment->isImage())
                                        <i class="fas fa-image text-info me-2"></i>
                                        @elseif($attachment->isPdf())
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        @elseif($attachment->isDocument())
                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                        @else
                                        <i class="fas fa-file text-secondary me-2"></i>
                                        @endif
                                        <div>
                                            <div>{{ $attachment->filename }}</div>
                                            <small class="text-muted">{{ $attachment->formatted_size }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.emails.attachments.download', $attachment) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Email Body -->
                    <div class="row">
                        <div class="col-12">
                            <h6>Message</h6>
                            <div class="border rounded p-3" style="max-height: 600px; overflow-y: auto;">
                                @if($email->body_html)
                                {!! $email->body_html !!}
                                @else
                                <pre style="white-space: pre-wrap; font-family: inherit;">{{ $email->body_text }}</pre>
                                @endif
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
function markAsImportant(emailId) {
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
            location.reload();
        }
    });
}

function archive(emailId) {
    fetch(`{{ route('admin.emails.archive', '') }}/${emailId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
