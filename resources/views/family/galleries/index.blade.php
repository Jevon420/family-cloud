@extends('family.layouts.app')

@section('title', 'My Galleries')

@push('styles')
<style>
    /* Modern Gallery Animations */
    .gallery-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .gallery-image {
        transition: all 0.3s ease;
    }

    .gallery-card:hover .gallery-image {
        transform: scale(1.05);
    }

    .search-panel {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.8);
    }

    .dark .search-panel {
        background: rgba(30, 41, 59, 0.8);
    }

    .layout-toggle {
        transition: all 0.2s ease;
    }

    .layout-toggle.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 14px 0 rgba(102, 126, 234, 0.4);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Modern Header Section -->
    <div class="relative">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gradient mb-2">
                    <i class="fas fa-images mr-3"></i>My Galleries
                </h1>
                <p class="{{ $darkMode ? 'text-slate-400' : 'text-gray-600' }}">
                    Organize and share your precious memories
                </p>
            </div>
            <a href="{{ route('family.galleries.create') }}" class="btn-primary group">
                <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-90"></i>
                Create Gallery
            </a>
        </div>
    </div>

    <!-- Modern Search and Filter Panel -->
    <div class="card-modern p-6" x-data="{ filtersOpen: false }">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Search Input -->
            <div class="flex-1 max-w-md">
                <form method="GET" action="{{ route('family.galleries.index') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search {{ $darkMode ? 'text-slate-400' : 'text-gray-400' }}"></i>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search galleries..."
                           class="input-modern pl-10"
                           onchange="this.form.submit()">
                </form>
            </div>

            <!-- Filter Toggle & Layout Options -->
            <div class="flex items-center gap-3">
                <button @click="filtersOpen = !filtersOpen"
                        class="btn-secondary lg:hidden">
                    <i class="fas fa-filter mr-2"></i>
                    Filters
                </button>

                <!-- Layout Options -->
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
                    <button type="button"
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'list']) }}'"
                            class="layout-toggle p-2 rounded {{ $galleryLayout === 'list' ? 'active' : ($darkMode ? 'text-slate-400 hover:text-white' : 'text-gray-500 hover:text-gray-700') }}">
                        <i class="fas fa-list"></i>
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
            <form method="GET" action="{{ route('family.galleries.index') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    <a href="{{ route('family.galleries.index') }}" class="btn-secondary flex-1 text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop Filters -->
        <div class="hidden lg:block mt-4 pt-4 border-t {{ $darkMode ? 'border-slate-600' : 'border-gray-200' }}">
            <form method="GET" action="{{ route('family.galleries.index') }}" class="flex items-center gap-4">
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
                <a href="{{ route('family.galleries.index') }}" class="btn-secondary">
                    Reset
                </a>
            </form>
        </div>
    </div>

    @if($galleries->isEmpty())
        <!-- Empty State with Modern Design -->
        <div class="card-modern p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <i class="fas fa-images text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-2">
                No galleries yet
            </h3>
            <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mb-8 max-w-sm mx-auto">
                Start organizing your memories by creating your first gallery. Share photos and moments with your family.
            </p>
            <a href="{{ route('family.galleries.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Create Your First Gallery
            </a>
        </div>
    @else
        @if($galleryLayout === 'grid')
            <!-- Modern Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                <div class="gallery-card card-modern overflow-hidden group">
                    <!-- Gallery Image -->
                    <div class="relative aspect-w-16 aspect-h-10 overflow-hidden">
                        @if($gallery->cover_image)
                            <img src="{{ route('public.storage.signed-url', ['path' => $gallery->cover_image, 'type' => 'long']) }}"
                                 alt="{{ $gallery->name }}"
                                 class="gallery-image w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-images text-4xl text-white opacity-50"></i>
                            </div>
                        @endif

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <a href="{{ route('family.galleries.show', $gallery->slug) }}"
                               class="{{ $darkMode ? 'bg-slate-800 text-white' : 'bg-white text-gray-900' }} px-4 py-2 rounded-lg font-medium transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <i class="fas fa-eye mr-2"></i>View Gallery
                            </a>
                        </div>

                        <!-- Photo Count Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="badge-blue">
                                <i class="fas fa-images mr-1"></i>{{ $gallery->photos_count }}
                            </span>
                        </div>
                    </div>

                    <!-- Gallery Info -->
                    <div class="p-4">
                        <h3 class="font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-1 truncate">
                            {{ $gallery->name }}
                        </h3>
                        @if($gallery->description)
                            <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mb-3 line-clamp-2">
                                {{ Str::limit($gallery->description, 80) }}
                            </p>
                        @endif
                        <div class="flex items-center justify-between text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }} mb-3">
                            <span><i class="fas fa-calendar mr-1"></i>{{ $gallery->created_at->format('M j, Y') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('family.galleries.show', $gallery->slug) }}"
                               class="flex-1 btn-modern bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 text-center">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <a href="{{ route('family.galleries.edit', $gallery->slug) }}"
                               class="flex-1 btn-modern bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 text-center">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('family.galleries.update', $gallery->slug) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this gallery?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full btn-modern bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @elseif($galleryLayout === 'masonry')
            <!-- Modern Masonry Layout -->
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                @foreach($galleries as $gallery)
                <div class="gallery-card card-modern overflow-hidden break-inside-avoid group">
                    <!-- Gallery Image -->
                    <div class="relative overflow-hidden">
                        @if($gallery->cover_image)
                            <img src="{{ route('public.storage.signed-url', ['path' => $gallery->cover_image, 'type' => 'long']) }}"
                                 alt="{{ $gallery->name }}"
                                 class="gallery-image w-full">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-images text-4xl text-white opacity-50"></i>
                            </div>
                        @endif

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <a href="{{ route('family.galleries.show', $gallery->slug) }}"
                               class="{{ $darkMode ? 'bg-slate-800 text-white' : 'bg-white text-gray-900' }} px-4 py-2 rounded-lg font-medium transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <i class="fas fa-eye mr-2"></i>View Gallery
                            </a>
                        </div>

                        <!-- Photo Count Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="badge-blue">
                                <i class="fas fa-images mr-1"></i>{{ $gallery->photos_count }}
                            </span>
                        </div>
                    </div>

                    <!-- Gallery Info -->
                    <div class="p-4">
                        <h3 class="font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-2">
                            {{ $gallery->name }}
                        </h3>
                        @if($gallery->description)
                            <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mb-3">
                                {{ Str::limit($gallery->description, 100) }}
                            </p>
                        @endif
                        <div class="flex items-center justify-between text-xs {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }} mb-3">
                            <span><i class="fas fa-calendar mr-1"></i>{{ $gallery->created_at->format('M j, Y') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('family.galleries.show', $gallery->slug) }}"
                               class="flex-1 btn-modern bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 text-center">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <a href="{{ route('family.galleries.edit', $gallery->slug) }}"
                               class="flex-1 btn-modern bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 text-center">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('family.galleries.update', $gallery->slug) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this gallery?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full btn-modern bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Modern List Layout -->
            <div class="card-modern overflow-hidden">
                <div class="divide-y {{ $darkMode ? 'divide-slate-700' : 'divide-gray-200' }}">
                    @foreach($galleries as $gallery)
                    <div class="p-6 hover:{{ $darkMode ? 'bg-slate-700/50' : 'bg-gray-50' }} transition-colors duration-200">
                        <div class="flex items-center space-x-4">
                            <!-- Gallery Thumbnail -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-lg overflow-hidden">
                                    @if($gallery->cover_image)
                                        <img src="{{ route('public.storage.signed-url', ['path' => $gallery->cover_image, 'type' => 'long']) }}"
                                             alt="{{ $gallery->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <i class="fas fa-images text-white"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Gallery Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">
                                    {{ $gallery->name }}
                                </h3>
                                @if($gallery->description)
                                    <p class="text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-600' }} mt-1">
                                        {{ Str::limit($gallery->description, 120) }}
                                    </p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-sm {{ $darkMode ? 'text-slate-400' : 'text-gray-500' }}">
                                    <span><i class="fas fa-images mr-1"></i>{{ $gallery->photos_count }} photos</span>
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $gallery->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('family.galleries.show', $gallery->slug) }}"
                                   class="btn-modern bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('family.galleries.edit', $gallery->slug) }}"
                                   class="btn-modern bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('family.galleries.update', $gallery->slug) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-modern bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Modern Pagination -->
        <div class="mt-8">
            {{ $galleries->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modern image loading with fade-in effect
    const images = document.querySelectorAll('img');

    images.forEach(image => {
        image.addEventListener('load', function() {
            this.classList.add('animate-fade-in');
        });

        // Add lazy loading
        image.setAttribute('loading', 'lazy');
    });

    // Enhanced form submission with loading states
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            }
        });
    });
});
</script>
@endpush
@endsection
