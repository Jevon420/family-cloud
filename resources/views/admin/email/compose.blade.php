@extends('admin.layouts.app')

@section('title', 'Compose Email')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Compose Email
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.emails.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="configuration_id" class="form-label">From <span class="text-danger">*</span></label>
                                <select class="form-select @error('configuration_id') is-invalid @enderror"
                                        id="configuration_id" name="configuration_id" required>
                                    <option value="">Select Email Configuration</option>
                                    @foreach($configurations as $config)
                                    <option value="{{ $config->id }}" {{ (old('configuration_id') == $config->id || (!old('configuration_id') && isset($defaultConfigId) && $defaultConfigId == $config->id)) ? 'selected' : '' }}>
                                        {{ $config->name }} ({{ $config->email }})
                                        @if($config->is_default) - Default @endif
                                        @if($config->email == 'support@jevonredhead.com') - ✓ Working @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('configuration_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Use "Support Email" - it's the working configuration</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="to" class="form-label">To <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('to') is-invalid @enderror"
                                       id="to" name="to" value="{{ old('to') }}"
                                       placeholder="recipient@example.com, another@example.com" required>
                                @error('to')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Separate multiple email addresses with commas</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cc" class="form-label">CC</label>
                                <input type="text" class="form-control @error('cc') is-invalid @enderror"
                                       id="cc" name="cc" value="{{ old('cc') }}"
                                       placeholder="cc@example.com">
                                @error('cc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bcc" class="form-label">BCC</label>
                                <input type="text" class="form-control @error('bcc') is-invalid @enderror"
                                       id="bcc" name="bcc" value="{{ old('bcc') }}"
                                       placeholder="bcc@example.com">
                                @error('bcc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                   id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('body') is-invalid @enderror"
                                      id="body" name="body" rows="12" style="display: none;">{{ old('body') }}</textarea>
                            @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control @error('attachments.*') is-invalid @enderror"
                                   id="attachments" name="attachments[]" multiple>
                            @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 10MB per file. You can select multiple files.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.emails.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Emails
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-save me-1"></i>Save Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Send Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    ClassicEditor
        .create(document.querySelector('#body'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                ]
            }
        })
        .then(editor => {
            const form = document.querySelector('form');
            form.addEventListener('submit', (event) => {
                const editorData = editor.getData();

                // Check if the editor content is empty
                if (!editorData || editorData.trim() === '') {
                    event.preventDefault();
                    alert('Please enter a message before sending the email.');
                    return;
                }

                // Update the textarea with editor content
                const textarea = document.querySelector('#body');
                textarea.value = editorData;
            });
        })
        .catch(error => {
            console.error(error);
        });
});
</script>
@endpush
