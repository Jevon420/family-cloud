@if($emails->count() > 0)
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>From</th>
                <th>Subject</th>
                <th>Configuration</th>
                <th>Type</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emails as $email)
            <tr class="{{ $email->status === 'unread' ? 'fw-bold' : '' }}">
                <td>
                    <div class="d-flex align-items-center">
                        @if($email->is_important)
                        <i class="fas fa-star text-warning me-1"></i>
                        @endif
                        <div>
                            <div>{{ $email->from_name ?: $email->from_email }}</div>
                            <small class="text-muted">{{ $email->from_email }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        {{ $email->subject }}
                        @if($email->has_attachments)
                        <i class="fas fa-paperclip text-muted ms-1"></i>
                        @endif
                    </div>
                    <small class="text-muted">{{ $email->preview_text }}</small>
                </td>
                <td>
                    <span class="badge bg-secondary">{{ $email->emailConfiguration->name }}</span>
                </td>
                <td>
                    @if($email->type === 'received')
                    <span class="badge bg-info">Received</span>
                    @elseif($email->type === 'sent')
                    <span class="badge bg-success">Sent</span>
                    @endif
                </td>
                <td>
                    @if($email->status === 'unread')
                    <span class="badge bg-primary">Unread</span>
                    @elseif($email->status === 'read')
                    <span class="badge bg-success">Read</span>
                    @elseif($email->status === 'archived')
                    <span class="badge bg-secondary">Archived</span>
                    @endif
                </td>
                <td>{{ $email->sent_at->format('M d, Y') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.emails.show', $email) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-outline-warning" onclick="markAsImportant({{ $email->id }})">
                            <i class="fas fa-star"></i>
                        </button>
                        <form action="{{ route('admin.emails.destroy', $email) }}" method="POST" class="d-inline">
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
    {{ $emails->links() }}
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
    <p class="text-muted">No emails found</p>
</div>
@endif
