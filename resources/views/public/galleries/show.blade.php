@extends('layouts.app')

@section('title', $gallery->name . ' Gallery')
@section('meta_description', 'View photos from ' . $gallery->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/galleries.css') }}">
@endpush

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="galleryApp()" x-init="init()">
    <!-- Hero Section with Gallery Info -->
    <div class="relative overflow-hidden">
        <!-- Background Image -->
        @if($gallery->cover_image)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $gallery->cover_image) }}"
                     alt="{{ $gallery->name }}"
                     class="w-full h-full object-cover filter blur-lg scale-110 opacity-30">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-900/80 via-blue-900/80 to-indigo-900/80"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-r from-purple-900 via-blue-900 to-indigo-900"></div>
        @endif

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-white transition-colors">
                            <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('galleries.index') }}" class="ml-1 text-sm font-medium text-gray-300 hover:text-white md:ml-2 transition-colors">Galleries</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-300 md:ml-2">{{ $gallery->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Gallery Header -->
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">{{ $gallery->name }}</h1>
                @if($gallery->description)
                    <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-8">{{ $gallery->description }}</p>
                @endif
                <div class="flex flex-wrap justify-center items-center gap-6 text-sm text-gray-300 mb-8">
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $photos->total() }} photos
                    </span>
                    <span>•</span>
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Created {{ $gallery->created_at->format('F j, Y') }}
                    </span>
                    @if($gallery->updated_at->ne($gallery->created_at))
                        <span>•</span>
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Updated {{ $gallery->updated_at->format('F j, Y') }}
                        </span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-center gap-4">
                    @php $darkMode = true; @endphp
                    @include('family.partials.share-button', ['media' => $gallery, 'mediaType' => 'gallery'])
                    <button type="button" class="inline-flex items-center px-6 py-3 border border-white/30 shadow-sm text-sm leading-4 font-medium rounded-xl text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 transition-all duration-200">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download All
                    </button>
                    <button @click="toggleSlideshow()" class="inline-flex items-center px-6 py-3 border border-white/30 shadow-sm text-sm leading-4 font-medium rounded-xl text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 transition-all duration-200">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-9-9v8a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                        </svg>
                        Slideshow
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters and Controls -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-4 items-center">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="applyFilters()"
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Search photos...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="relative">
                        <select x-model="sortBy" @change="applySorting()"
                                class="appearance-none bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="created_at_desc">Newest First</option>
                            <option value="created_at_asc">Oldest First</option>
                            <option value="name_asc">Name A-Z</option>
                            <option value="name_desc">Name Z-A</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Photo Type Filter -->
                    <div class="relative">
                        <select x-model="typeFilter" @change="applyFilters()"
                                class="appearance-none bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Types</option>
                            <option value="image/jpeg">JPEG</option>
                            <option value="image/png">PNG</option>
                            <option value="image/gif">GIF</option>
                            <option value="image/webp">WebP</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <button @click="clearFilters()"
                            x-show="hasActiveFilters()"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Clear Filters
                    </button>
                </div>

                <!-- Layout and View Controls -->
                <div class="flex items-center gap-4">
                    <!-- Layout Controls -->
                    <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                        <button @click="setLayout('grid')"
                                :class="layout === 'grid' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                        <button @click="setLayout('masonry')"
                                :class="layout === 'masonry' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </button>
                        <button @click="setLayout('justified')"
                                :class="layout === 'justified' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4m-4 0l5.172 5.172a2.828 2.828 0 010 4L3 19.5M15 8V4m0 0h4m-4 0l5.172 5.172a2.828 2.828 0 010 4L15 19.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Selection Mode -->
                    <button @click="toggleSelectionMode()"
                            :class="selectionMode ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                        <span x-show="!selectionMode">Select</span>
                        <span x-show="selectionMode" x-text="'Selected (' + selectedPhotos.length + ')'"></span>
                    </button>
                </div>
            </div>

            <!-- Selection Actions -->
            <div x-show="selectionMode && selectedPhotos.length > 0"
                 x-transition:enter="transition-all duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex flex-wrap gap-3 items-center">
                    <button @click="downloadSelected()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Selected
                    </button>
                    <button @click="shareSelected()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                        </svg>
                        Share Selected
                    </button>
                    <button @click="selectAll()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Select All
                    </button>
                    <button @click="clearSelection()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Clear Selection
                    </button>
                </div>
            </div>

            <!-- Results Info -->
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                <div>
                    Showing {{ $photos->count() }} photos
                </div>
            </div>
        </div>
        <!-- Photos Container -->
        @if($photos->count() > 0)
            <!-- Photo Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer"
                         @click="!selectionMode && openPhotoModal({{ json_encode([
                             'id' => $photo->id,
                             'title' => $photo->title ?? $photo->name ?? 'Untitled',
                             'url' => $photo->signed_url,
                             'thumbnail_url' => $photo->signed_thumbnail_url ?? $photo->signed_url,
                             'created_at' => $photo->created_at->toISOString(),
                             'description' => $photo->description ?? null
                         ]) }})">
                        <!-- Selection Checkbox -->
                        <div x-show="selectionMode" class="absolute top-4 left-4 z-10" @click.stop>
                            <input type="checkbox"
                                   value="{{ $photo->id }}"
                                   class="w-5 h-5 text-purple-600 bg-white rounded-lg border-2 border-white shadow-lg focus:ring-purple-500">
                        </div>                        <!-- Photo Image -->
                        <div class="aspect-square relative overflow-hidden">
                            <x-lazy-image
                                :src="$photo->signed_thumbnail_url ?? $photo->signed_url"
                                :alt="$photo->title ?? $photo->name ?? 'Photo'"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />

                            <!-- Overlay with actions -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex items-center justify-between text-white">
                                        <div>
                                            <h3 class="font-medium text-sm truncate">{{ $photo->title ?? $photo->name ?? 'Untitled' }}</h3>
                                            <p class="text-xs text-gray-300">{{ $photo->created_at->format('M j, Y') }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button @click.stop="openPhotoModal({{ json_encode([
                                                'id' => $photo->id,
                                                'title' => $photo->title ?? $photo->name ?? 'Untitled',
                                                'url' => $photo->signed_url,
                                                'thumbnail_url' => $photo->signed_thumbnail_url ?? $photo->signed_url,
                                                'created_at' => $photo->created_at->toISOString(),
                                                'description' => $photo->description ?? null
                                            ]) }})" class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button @click.stop onclick="downloadPhoto({{ $photo->id }})" class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $photos->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No photos in this gallery</h3>
                    <p class="text-gray-600 mb-8">This gallery doesn't have any photos yet. Check back later for new additions!</p>
                    <a href="{{ route('galleries.index') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Galleries
                    </a>
                </div>
            </div>
        @endif

        <!-- Lightbox Modal -->
        <div x-show="showLightbox"
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
             @click="closeLightbox()"
             @keydown.escape.window="closeLightbox()">
            <div class="relative max-w-7xl max-h-full p-4" @click.stop>
                <!-- Close Button -->
                <button @click="closeLightbox()" class="absolute top-4 right-4 z-10 p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Navigation Buttons -->
                <button @click="previousPhoto()"
                        x-show="currentPhotoIndex > 0"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 p-3 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button @click="nextPhoto()"
                        x-show="currentPhotoIndex < allPhotos.length - 1"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 p-3 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Image -->
                <div class="text-center">
                    <!-- Loading state -->
                    <div x-show="!currentPhoto" class="flex items-center justify-center h-96">
                        <div class="text-white text-center">
                            <svg class="animate-spin h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p>Loading...</p>
                        </div>
                    </div>

                    <!-- Photo -->
                    <img x-show="currentPhoto"
                         :src="currentPhoto?.url"
                         :alt="currentPhoto?.title || 'Photo'"
                         class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl"
                         @load="$el.style.opacity = 1"
                         style="opacity: 0; transition: opacity 0.3s;">

                    <!-- Photo Info -->
                    <div x-show="currentPhoto" class="mt-4 text-white text-center">
                        <h3 class="text-xl font-semibold mb-2" x-text="currentPhoto?.title || 'Untitled'"></h3>
                        <p class="text-gray-300 mb-1" x-text="currentPhoto && new Date(currentPhoto.created_at).toLocaleDateString()"></p>
                        <p class="text-gray-400 text-sm" x-text="'Photo ' + (currentPhotoIndex + 1) + ' of ' + allPhotos.length"></p>
                        <div x-show="currentPhoto?.description" class="mt-2">
                            <p class="text-gray-300 text-sm max-w-md mx-auto" x-text="currentPhoto?.description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slideshow Modal -->
        <div x-show="showSlideshow"
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-black"
             @keydown.escape.window="toggleSlideshow()">
            <div class="relative w-full h-full flex items-center justify-center">
                <!-- Controls -->
                <div class="absolute top-4 left-4 right-4 flex items-center justify-between z-10">
                    <div class="flex items-center space-x-4">
                        <button @click="toggleSlideshow()" class="p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <button @click="toggleSlideshowPlay()" class="p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                            <svg x-show="!slideshowPlaying" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-9-9v8a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                            </svg>
                            <svg x-show="slideshowPlaying" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" />
                            </svg>
                        </button>
                    </div>
                    <div class="text-white text-sm">
                        <span x-text="(currentPhotoIndex + 1) + ' / ' + filteredPhotos.length"></span>
                    </div>
                </div>

                <!-- Current Image -->
                <div class="w-full h-full flex items-center justify-center p-8">
                    <img x-show="currentPhoto"
                         :src="currentPhoto?.url"
                         :alt="currentPhoto?.title || 'Photo'"
                         class="max-w-full max-h-full object-contain">
                </div>

                <!-- Navigation -->
                <button @click="previousPhoto()"
                        x-show="currentPhotoIndex > 0"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 p-3 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button @click="nextPhoto()"
                        x-show="currentPhotoIndex < filteredPhotos.length - 1"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 p-3 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Photo Info -->
                <div class="absolute bottom-4 left-4 right-4 text-center text-white">
                    <h3 class="text-2xl font-semibold mb-2" x-text="currentPhoto?.title || 'Untitled'"></h3>
                    <p class="text-gray-300" x-text="currentPhoto && new Date(currentPhoto.created_at).toLocaleDateString()"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('family.partials.sharing-modal', ['darkMode' => false])
@endsection

@section('scripts')
<script>
function galleryApp() {
    return {
        // Data
        allPhotos: @json($photos->items()),
        filteredPhotos: [],
        paginatedPhotos: [],

        // UI State
        layout: 'grid',
        loading: false,
        searchQuery: '',
        sortBy: 'created_at_desc',
        typeFilter: '',

        // Selection
        selectionMode: false,
        selectedPhotos: [],

        // Modal state
        showLightbox: false,
        currentPhoto: null,
        currentPhotoIndex: 0,

        // Modal functions
        openPhotoModal(photo) {
            this.currentPhoto = photo;
            this.currentPhotoIndex = this.allPhotos.findIndex(p => p.id === photo.id);
            this.showLightbox = true;
            document.body.style.overflow = 'hidden';
        },

        closeLightbox() {
            this.showLightbox = false;
            this.currentPhoto = null;
            document.body.style.overflow = '';
        },

        // Lightbox & Slideshow
        showLightbox: false,
        showSlideshow: false,
        currentPhoto: null,
        currentPhotoIndex: 0,
        slideshowPlaying: false,
        slideshowInterval: null,

        // Pagination
        currentPage: 1,
        itemsPerPage: 20,
        totalPages: 1,

        // Initialize
        init() {
            this.filteredPhotos = [...this.allPhotos];
            this.updatePagination();
            this.updatePaginatedPhotos();

            // Load layout preference from localStorage
            const savedLayout = localStorage.getItem('gallery-photo-layout');
            if (savedLayout) {
                this.layout = savedLayout;
            }

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (this.showLightbox || this.showSlideshow) {
                    if (e.key === 'ArrowLeft') this.previousPhoto();
                    if (e.key === 'ArrowRight') this.nextPhoto();
                    if (e.key === ' ') {
                        e.preventDefault();
                        if (this.showSlideshow) this.toggleSlideshowPlay();
                    }
                }
            });
        },

        // Filter functionality
        applyFilters() {
            this.loading = true;
            setTimeout(() => {
                let filtered = [...this.allPhotos];

                // Search filter
                if (this.searchQuery) {
                    const query = this.searchQuery.toLowerCase();
                    filtered = filtered.filter(photo =>
                        (photo.title && photo.title.toLowerCase().includes(query)) ||
                        (photo.description && photo.description.toLowerCase().includes(query)) ||
                        (photo.name && photo.name.toLowerCase().includes(query))
                    );
                }

                // Type filter
                if (this.typeFilter) {
                    filtered = filtered.filter(photo =>
                        photo.mime_type === this.typeFilter
                    );
                }

                this.filteredPhotos = filtered;
                this.applySorting();
                this.currentPage = 1;
                this.updatePagination();
                this.updatePaginatedPhotos();
                this.loading = false;
            }, 300);
        },

        // Sorting functionality
        applySorting() {
            const [field, direction] = this.sortBy.split('_');

            this.filteredPhotos.sort((a, b) => {
                let aValue = a[field] || a.name; // fallback to name if field doesn't exist
                let bValue = b[field] || b.name;

                // Handle date sorting
                if (field === 'created_at' || field === 'updated_at') {
                    aValue = new Date(aValue);
                    bValue = new Date(bValue);
                }

                // Handle string sorting
                if (typeof aValue === 'string') {
                    aValue = aValue.toLowerCase();
                    bValue = bValue.toLowerCase();
                }

                if (direction === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });

            this.updatePaginatedPhotos();
        },

        // Layout functionality
        setLayout(newLayout) {
            this.layout = newLayout;
            localStorage.setItem('gallery-photo-layout', newLayout);
        },

        // Selection functionality
        toggleSelectionMode() {
            this.selectionMode = !this.selectionMode;
            if (!this.selectionMode) {
                this.selectedPhotos = [];
            }
        },

        togglePhotoSelection(photoId) {
            const index = this.selectedPhotos.indexOf(photoId);
            if (index > -1) {
                this.selectedPhotos.splice(index, 1);
            } else {
                this.selectedPhotos.push(photoId);
            }
        },

        selectAll() {
            this.selectedPhotos = this.filteredPhotos.map(photo => photo.id);
        },

        clearSelection() {
            this.selectedPhotos = [];
        },

        downloadSelected() {
            // Implement download selected photos
            console.log('Downloading selected photos:', this.selectedPhotos);
            alert('Download functionality would be implemented here');
        },

        shareSelected() {
            // Implement share selected photos
            console.log('Sharing selected photos:', this.selectedPhotos);
            alert('Share functionality would be implemented here');
        },

        downloadPhoto(photo) {
            // Implement single photo download
            console.log('Downloading photo:', photo);
            alert('Single photo download would be implemented here');
        },

        // Lightbox functionality
        openLightbox(photo) {
            this.currentPhoto = photo;
            this.currentPhotoIndex = this.filteredPhotos.findIndex(p => p.id === photo.id);
            this.showLightbox = true;
            document.body.style.overflow = 'hidden';
        },

        closeLightbox() {
            this.showLightbox = false;
            this.currentPhoto = null;
            document.body.style.overflow = '';
        },

        // Slideshow functionality
        toggleSlideshow() {
            this.showSlideshow = !this.showSlideshow;
            if (this.showSlideshow) {
                if (!this.currentPhoto && this.filteredPhotos.length > 0) {
                    this.currentPhoto = this.filteredPhotos[0];
                    this.currentPhotoIndex = 0;
                }
                document.body.style.overflow = 'hidden';
            } else {
                this.slideshowPlaying = false;
                if (this.slideshowInterval) {
                    clearInterval(this.slideshowInterval);
                }
                document.body.style.overflow = '';
            }
        },

        toggleSlideshowPlay() {
            this.slideshowPlaying = !this.slideshowPlaying;
            if (this.slideshowPlaying) {
                this.slideshowInterval = setInterval(() => {
                    this.nextPhoto();
                }, 3000);
            } else {
                clearInterval(this.slideshowInterval);
            }
        },

        // Navigation
        previousPhoto() {
            if (this.currentPhotoIndex > 0) {
                this.currentPhotoIndex--;
                this.currentPhoto = this.allPhotos[this.currentPhotoIndex];
            }
        },

        nextPhoto() {
            if (this.currentPhotoIndex < this.allPhotos.length - 1) {
                this.currentPhotoIndex++;
                this.currentPhoto = this.allPhotos[this.currentPhotoIndex];
            }
        },

        // Pagination functionality
        updatePagination() {
            this.totalPages = Math.ceil(this.filteredPhotos.length / this.itemsPerPage);
        },

        updatePaginatedPhotos() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            this.paginatedPhotos = this.filteredPhotos.slice(start, end);
        },

        setPage(page) {
            this.currentPage = page;
            this.updatePaginatedPhotos();
            window.scrollTo({ top: 400, behavior: 'smooth' });
        },

        getPageNumbers() {
            const pages = [];
            const maxVisible = 5;
            const start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
            const end = Math.min(this.totalPages, start + maxVisible - 1);

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            return pages;
        },

        // Filter helpers
        hasActiveFilters() {
            return false; // Simplified for now
        },

        clearFilters() {
            // Simplified for now
        }
    }
}
</script>
@endsection
