@extends('family.layouts.app')

@section('title', $gallery->name)

@push('styles')
<style>
    /* Modern Gallery Show Styles */
    .photo-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .photo-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .photo-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .photo-card:hover .photo-overlay {
        opacity: 1;
    }

    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
    }

    .carousel-modal {
        backdrop-filter: blur(20px);
        background: rgba(0, 0, 0, 0.9);
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .upload-modal {
        backdrop-filter: blur(10px);
    }

    .layout-toggle.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 14px 0 rgba(102, 126, 234, 0.4);
    }
</style>
@endpush

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Modern Gallery Header -->
    <div class="card-modern p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Gallery Info -->
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-images text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gradient">{{ $gallery->name }}</h1>
                        <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }}">
                            <i class="fas fa-calendar mr-1"></i>Created {{ $gallery->created_at->format('M j, Y') }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-images mr-1"></i>{{ $photos->total() }} photos
                        </p>
                    </div>
                </div>
                @if($gallery->description)
                    <p class="{{ $darkMode ? 'text-slate-300' : 'text-gray-700' }}">{{ $gallery->description }}</p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('family.galleries.index') }}" class="btn-secondary group">
                    <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i>
                    Back to Galleries
                </a>

                @include('family.partials.share-button', ['media' => $gallery, 'mediaType' => 'gallery'])

                <button type="button" x-data="{}" x-on:click="$dispatch('open-modal')" class="btn-primary group">
                    <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-90"></i>
                    Add Photos
                </button>

                @if($photos->isNotEmpty())
                    <button type="button" onclick="openModal({{ $photos->first()->id }})" class="btn-success">
                        <i class="fas fa-play mr-2"></i>
                        Slideshow
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modern Controls Panel -->
    <div class="card-modern p-6" x-data="{ filtersOpen: false }">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <form method="GET" action="{{ route('family.galleries.show', $gallery->slug) }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search {{ $darkMode ? 'text-slate-400' : 'text-gray-400' }}"></i>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search photos..."
                           class="input-modern pl-10"
                           onchange="this.form.submit()">
                </form>
            </div>

            <!-- Layout Options -->
            <div class="flex items-center gap-3">
                <button @click="filtersOpen = !filtersOpen"
                        class="btn-secondary lg:hidden">
                    <i class="fas fa-filter mr-2"></i>
                    Filters
                </button>

                <div class="flex rounded-lg border {{ $darkMode ? 'border-slate-600' : 'border-gray-200' }} p-1">
                    <button type="button"
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'grid']) }}'"
                            class="layout-toggle p-2 rounded {{ $galleryLayout === 'grid' ? 'active' : ($darkMode ? 'text-slate-400 hover:text-white' : 'text-gray-500 hover:text-gray-700') }}">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button"
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'masonry']) }}'"
                            class="layout-toggle p-2 rounded {{ $galleryLayout === 'masonry' ? 'active' : ($darkMode ? 'text-slate-400 hover:text-white' : 'text-gray-500 hover:text-gray-700') }}">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div x-show="filtersOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mt-4 pt-4 border-t {{ $darkMode ? 'border-slate-600' : 'border-gray-200' }} lg:hidden">
            <form method="GET" action="{{ route('family.galleries.show', $gallery->slug) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div>
                    <label class="block text-sm font-medium {{ $darkMode ? 'text-slate-300' : 'text-gray-700' }} mb-2">
                        Sort By
                    </label>
                    <select name="sort" class="input-modern">
                        <option value="">Default</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium {{ $darkMode ? 'text-slate-300' : 'text-gray-700' }} mb-2">
                        Order
                    </label>
                    <select name="sort_order" class="input-modern">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="sm:col-span-2 flex gap-3">
                    <button type="submit" class="btn-primary flex-1">
                        Apply Filters
                    </button>
                    <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="btn-secondary flex-1 text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop Filters -->
        <div class="hidden lg:block mt-4 pt-4 border-t {{ $darkMode ? 'border-slate-600' : 'border-gray-200' }}">
            <form method="GET" action="{{ route('family.galleries.show', $gallery->slug) }}" class="flex items-center gap-4">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="sort" class="input-modern w-auto">
                    <option value="">Sort By</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                </select>
                <select name="sort_order" class="input-modern w-auto">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
                <button type="submit" class="btn-primary">
                    Apply
                </button>
                <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="btn-secondary">
                    Reset
                </a>
            </form>
        </div>
    </div>


    @if($photos->isEmpty())
        <!-- Modern Empty State -->
        <div class="card-modern p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <i class="fas fa-camera text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-2">
                No photos in this gallery yet
            </h3>
            <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mb-8 max-w-sm mx-auto">
                Start building your gallery by uploading your favorite photos and memories.
            </p>
            <button type="button" x-data="{}" x-on:click="$dispatch('open-modal')" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Add Your First Photos
            </button>
        </div>
    @else
        @if($galleryLayout === 'grid')
            <!-- Modern Grid Layout -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($photos as $photo)
                <div class="photo-card group relative overflow-hidden rounded-lg cursor-pointer"
                     data-photo-id="{{ $photo->id }}"
                     onclick="openModal({{ $photo->id }})">
                    <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                        <img src="{{ $photo->signed_thumbnail_url }}"
                             alt="{{ $photo->name }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 wasabi-monitored"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">

                        <!-- Photo Overlay -->
                        <div class="photo-overlay absolute inset-0 flex items-end p-3">
                            <div class="text-white">
                                <h4 class="font-medium text-sm truncate">{{ $photo->name }}</h4>
                                <p class="text-xs opacity-75">{{ $photo->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>

                        <!-- Play Icon -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="w-12 h-12 {{ $darkMode ? 'bg-slate-800 bg-opacity-90' : 'bg-white bg-opacity-90' }} rounded-full flex items-center justify-center">
                                <i class="fas fa-play {{ $darkMode ? 'text-white' : 'text-gray-800' }} ml-0.5"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Modern Masonry Layout -->
            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 xl:columns-5 gap-4">
                @foreach($photos as $photo)
                <div class="masonry-item photo-card group relative overflow-hidden rounded-lg cursor-pointer break-inside-avoid"
                     data-photo-id="{{ $photo->id }}"
                     onclick="openModal({{ $photo->id }})">
                    <div class="relative overflow-hidden">
                        <img src="{{ $photo->signed_thumbnail_url }}"
                             alt="{{ $photo->name }}"
                             class="w-full transition-transform duration-300 group-hover:scale-105 wasabi-monitored"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">

                        <!-- Photo Overlay -->
                        <div class="photo-overlay absolute inset-0 flex items-end p-4">
                            <div class="text-white">
                                <h4 class="font-medium text-sm">{{ $photo->name }}</h4>
                                <p class="text-xs opacity-75 mt-1">{{ $photo->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>

                        <!-- Play Icon -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="w-16 h-16 {{ $darkMode ? 'bg-slate-800 bg-opacity-90' : 'bg-white bg-opacity-90' }} rounded-full flex items-center justify-center">
                                <i class="fas fa-play {{ $darkMode ? 'text-white' : 'text-gray-800' }} text-lg ml-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- Modern Pagination -->
        <div class="mt-8">
            {{ $photos->links() }}
        </div>
    @endif

    <!-- Modern Photo Upload Modal -->
    <div x-data="{ open: false }"
         x-on:open-modal.window="open = true"
         x-on:close-modal.window="open = false"
         x-show="open"
         class="fixed inset-0 overflow-y-auto z-50 upload-modal"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom card-modern overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-cloud-upload-alt text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gradient">Upload Photos</h3>
                        </div>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('family.galleries.photos.upload', $gallery->slug) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="uploadForm">
                        @csrf

                        <!-- Drag & Drop Upload Area -->
                        <div class="border-2 border-dashed {{ $darkMode ? 'border-slate-600' : 'border-gray-300' }} rounded-lg p-8 text-center hover:border-blue-500 transition-colors duration-200"
                             id="dropZone">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-images text-2xl {{ $darkMode ? 'text-slate-400' : 'text-gray-400' }}"></i>
                            </div>
                            <h4 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-2">
                                Choose photos or drag & drop
                            </h4>
                            <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mb-4">
                                Select multiple photos to upload to this gallery
                            </p>
                            <input type="file"
                                   id="photos"
                                   name="photos[]"
                                   multiple
                                   accept="image/*"
                                   required
                                   class="hidden">
                            <button type="button"
                                    onclick="document.getElementById('photos').click()"
                                    class="btn-secondary">
                                <i class="fas fa-folder-open mr-2"></i>
                                Choose Files
                            </button>
                            <p class="text-xs {{ $darkMode ? 'text-slate-500' : 'text-gray-500' }} mt-2">
                                Supported: JPG, PNG, GIF. Max 5MB per photo
                            </p>
                        </div>

                        <!-- Selected Files Preview -->
                        <div id="filePreview" class="hidden mt-6">
                            <h4 class="text-sm font-medium {{ $darkMode ? 'text-slate-300' : 'text-gray-700' }} mb-3">
                                Selected Photos:
                            </h4>
                            <div id="previewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-6 pt-6 border-t {{ $darkMode ? 'border-slate-600' : 'border-gray-200' }}">
                            <button type="button" @click="open = false" class="btn-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary" id="uploadBtn">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>
                                Upload Photos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Photo Carousel Modal -->
    <div id="photoCarouselModal" class="carousel-modal fixed inset-0 z-50 hidden items-center justify-center">
        <div class="relative w-full h-full flex flex-col">
            <!-- Header Controls -->
            <div class="absolute top-0 left-0 right-0 z-20 bg-gradient-to-b from-black/50 to-transparent p-6">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 id="modalPhotoTitle" class="text-xl font-semibold"></h3>
                        <p id="modalPhotoDate" class="text-sm opacity-75"></p>
                    </div>
                    <button id="closeCarouselModal"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center transition-colors backdrop-blur-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Main Photo Display -->
            <div class="flex-1 flex items-center justify-center p-4">
                <div class="relative max-w-6xl max-h-full">
                    <!-- Navigation Buttons -->
                    <button id="prevPhotoBtn"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center transition-all z-10 backdrop-blur-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="nextPhotoBtn"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center transition-all z-10 backdrop-blur-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Photo Container -->
                    <div class="relative">
                        <img id="modalPhoto"
                             src=""
                             alt=""
                             class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl wasabi-monitored">
                    </div>
                </div>
            </div>

            <!-- Thumbnail Strip -->
            <div class="bg-gradient-to-t from-black/50 to-transparent backdrop-blur-sm p-4">
                <div class="flex space-x-3 overflow-x-auto scrollbar-hide justify-center" id="photoThumbnails">
                    <!-- Thumbnails will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modern photo upload functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('photos');
    const filePreview = document.getElementById('filePreview');
    const previewContainer = document.getElementById('previewContainer');
    const uploadForm = document.getElementById('uploadForm');
    const uploadBtn = document.getElementById('uploadBtn');

    // Drag and drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    }

    function unhighlight() {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            handleFiles(files);
        }
    }

    // File input change handler
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        previewContainer.innerHTML = '';

        if (files.length > 0) {
            filePreview.classList.remove('hidden');

            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'relative group';
                        preview.innerHTML = `
                            <img src="${e.target.result}"
                                 alt="Preview ${index + 1}"
                                 class="w-full h-20 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs font-medium">${file.name}</span>
                            </div>
                        `;
                        previewContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            filePreview.classList.add('hidden');
        }
    }

    // Form submission with loading state
    uploadForm.addEventListener('submit', function() {
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
    });
});

// Enhanced photo carousel functionality
const photoData = <?php echo json_encode($photos->map(function($photo) {
    return [
        'id' => $photo->id,
        'url' => route('public.storage.signed-url', ['path' => $photo->file_path, 'type' => 'long']),
        'title' => $photo->name,
        'date' => $photo->created_at->format('F j, Y')
    ];
})->values()->all()); ?>;

let currentPhotoIndex = 0;
const modal = document.getElementById('photoCarouselModal');
const modalPhoto = document.getElementById('modalPhoto');
const modalPhotoTitle = document.getElementById('modalPhotoTitle');
const modalPhotoDate = document.getElementById('modalPhotoDate');
const thumbnailContainer = document.getElementById('photoThumbnails');

// Generate thumbnails with modern styling
function generateThumbnails() {
    thumbnailContainer.innerHTML = '';
    photoData.forEach((photo, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = `flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden cursor-pointer transition-all duration-300 ${
            index === currentPhotoIndex
                ? 'ring-2 ring-white scale-110 opacity-100'
                : 'opacity-60 hover:opacity-100 hover:scale-105'
        }`;
        thumbnail.innerHTML = `<img src="${photo.url}" alt="${photo.title}" class="w-full h-full object-cover wasabi-monitored">`;
        thumbnail.onclick = () => showPhoto(index);
        thumbnailContainer.appendChild(thumbnail);
    });
}

// Show photo at index with smooth transitions
function showPhoto(index) {
    if (index < 0 || index >= photoData.length) return;

    currentPhotoIndex = index;
    const photo = photoData[index];

    // Add loading state
    modalPhoto.style.opacity = '0.5';

    modalPhoto.onload = function() {
        modalPhoto.style.opacity = '1';
    };

    modalPhoto.src = photo.url;
    modalPhoto.alt = photo.title;
    modalPhotoTitle.textContent = photo.title;
    modalPhotoDate.textContent = photo.date;

    generateThumbnails();

    // Scroll to current thumbnail
    const activeThumbnail = thumbnailContainer.children[index];
    if (activeThumbnail) {
        activeThumbnail.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
    }
}

// Open modal with animation
function openModal(photoId) {
    const photoIndex = photoData.findIndex(photo => photo.id == photoId);
    if (photoIndex !== -1) {
        showPhoto(photoIndex);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Trigger fade in animation
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

// Close modal with animation
function closeModal() {
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }, 200);
}

// Event listeners
document.getElementById('closeCarouselModal').onclick = closeModal;
document.getElementById('prevPhotoBtn').onclick = () => {
    const newIndex = currentPhotoIndex - 1 >= 0 ? currentPhotoIndex - 1 : photoData.length - 1;
    showPhoto(newIndex);
};
document.getElementById('nextPhotoBtn').onclick = () => {
    const newIndex = currentPhotoIndex + 1 < photoData.length ? currentPhotoIndex + 1 : 0;
    showPhoto(newIndex);
};

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (!modal.classList.contains('hidden')) {
        switch(e.key) {
            case 'Escape':
                closeModal();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                document.getElementById('prevPhotoBtn').click();
                break;
            case 'ArrowRight':
                e.preventDefault();
                document.getElementById('nextPhotoBtn').click();
                break;
        }
    }
});

// Touch/swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

modalPhoto.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

modalPhoto.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const swipeThreshold = 50;

    if (touchEndX < touchStartX - swipeThreshold) {
        document.getElementById('nextPhotoBtn').click();
    }
    if (touchEndX > touchStartX + swipeThreshold) {
        document.getElementById('prevPhotoBtn').click();
    }
});
</script>

@include('family.partials.sharing-modal')
@endpush
@endsection
