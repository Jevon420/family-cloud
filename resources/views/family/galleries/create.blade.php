@extends('family.layouts.app')

@section('title', 'Create New Gallery')

@push('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dark .form-container {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.1) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .upload-area {
        transition: all 0.3s ease;
        border: 2px dashed #d1d5db;
    }

    .upload-area:hover {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }

    .upload-area.dragover {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        transform: scale(1.02);
    }
</style>
@endpush

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Modern Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gradient mb-2">
                <i class="fas fa-plus-circle mr-3"></i>Create New Gallery
            </h1>
            <p class="{{ $darkMode ? 'text-slate-400' : 'text-gray-600' }}">
                Start organizing your memories into a beautiful gallery
            </p>
        </div>
        <a href="{{ route('family.galleries.index') }}" class="btn-secondary group">
            <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i>
            Back to Galleries
        </a>
    </div>

    <!-- Modern Form Container -->
    <div class="max-w-2xl mx-auto">
        <div class="card-modern p-8">
            <form action="{{ route('family.galleries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Gallery Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold {{ $darkMode ? 'text-slate-300' : 'text-gray-700' }} mb-2">
                        <i class="fas fa-heading mr-2"></i>Gallery Title
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           class="input-modern"
                           placeholder="Enter a memorable title for your gallery">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Gallery Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold {{ $darkMode ? 'text-slate-300' : 'text-gray-700' }} mb-2">
                        <i class="fas fa-align-left mr-2"></i>Description (Optional)
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="input-modern resize-none"
                              placeholder="Describe what this gallery contains...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Cover Image Upload -->
                <div>
                    <label for="cover_image" class="block text-sm font-semibold {{ $darkMode ? 'text-slate-300' : 'text-gray-700' }} mb-2">
                        <i class="fas fa-image mr-2"></i>Cover Image (Optional)
                    </label>

                    <!-- Modern Upload Area -->
                    <div class="upload-area rounded-xl p-8 text-center {{ $darkMode ? 'border-slate-600' : 'border-gray-300' }}"
                         id="uploadArea">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-2">
                            Upload Cover Image
                        </h3>
                        <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mb-4">
                            Drag and drop an image here, or click to browse
                        </p>
                        <input type="file"
                               id="cover_image"
                               name="cover_image"
                               accept="image/*"
                               class="hidden"
                               onchange="handleFileSelect(this)">
                        <button type="button"
                                onclick="document.getElementById('cover_image').click()"
                                class="btn-secondary">
                            <i class="fas fa-folder-open mr-2"></i>
                            Choose File
                        </button>
                        <p class="text-xs {{ $darkMode ? 'text-slate-500' : 'text-gray-500' }} mt-2">
                            Supported formats: JPG, PNG, GIF. Max size: 2MB
                        </p>
                    </div>

                    <!-- Preview Area -->
                    <div id="imagePreview" class="hidden mt-4">
                        <div class="relative inline-block">
                            <img id="previewImg" class="w-32 h-32 object-cover rounded-lg shadow-md" src="" alt="Preview">
                            <button type="button"
                                    onclick="removePreview()"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>

                    @error('cover_image')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6">
                    <button type="submit" class="btn-primary group" id="submitBtn">
                        <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-90"></i>
                        Create Gallery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// File upload handling
function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removePreview() {
    document.getElementById('cover_image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('cover_image');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        uploadArea.classList.add('dragover');
    }

    function unhighlight() {
        uploadArea.classList.remove('dragover');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    }

    // Form submission with loading state
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Gallery...';
    });
});
</script>
@endpush
@endsection
