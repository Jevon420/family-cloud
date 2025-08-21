@extends('layouts.app')

@section('title', 'Galleries')
@section('meta_description', 'Browse our collection of family photo galleries')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/galleries.css') }}">
@endpush

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="galleriesApp()" x-init="init()">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-purple-900 via-blue-900 to-indigo-900">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Photo <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Galleries</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-8">
                    Discover and explore our beautiful collection of family memories, special moments, and unforgettable experiences
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <div class="relative w-full max-w-md">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="performSearch()"
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 bg-white/90 backdrop-blur-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                               placeholder="Search galleries...">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
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
                    <!-- Sort By -->
                    <div class="relative">
                        <select x-model="sortBy" @change="applySorting()"
                                class="appearance-none bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="created_at_desc">Newest First</option>
                            <option value="created_at_asc">Oldest First</option>
                            <option value="name_asc">Name A-Z</option>
                            <option value="name_desc">Name Z-A</option>
                            <option value="photos_count_desc">Most Photos</option>
                            <option value="photos_count_asc">Least Photos</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="relative">
                        <select x-model="dateFilter" @change="applyFilters()"
                                class="appearance-none bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Photo Count Filter -->
                    <div class="relative">
                        <select x-model="photoCountFilter" @change="applyFilters()"
                                class="appearance-none bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Any Size</option>
                            <option value="1-10">1-10 Photos</option>
                            <option value="11-50">11-50 Photos</option>
                            <option value="51-100">51-100 Photos</option>
                            <option value="100+">100+ Photos</option>
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
                    <button @click="setLayout('list')"
                            :class="layout === 'list' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Active Filters Display -->
            <div x-show="hasActiveFilters()" class="mt-4 flex flex-wrap gap-2">
                <template x-for="filter in getActiveFilters()" :key="filter.key">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                        <span x-text="filter.label"></span>
                        <button @click="removeFilter(filter.key)" class="ml-2 hover:text-purple-600">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                </template>
            </div>
        </div>

        <!-- Galleries Container -->
        @if($galleries->count() > 0)
            <!-- Results Info -->
            <div class="mb-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing {{ $galleries->count() }} galleries
                </div>
            </div>

            <!-- Gallery Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                    <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Gallery Image -->
                        <div class="aspect-w-16 aspect-h-10 relative overflow-hidden">
                            <div class="w-full h-full">
                                @if($gallery->cover_image)
                                    <img src="{{ $gallery->cover_image ? $gallery->signed_cover_url : asset('/images/placeholder-gallery.jpg') }}"
                                         alt="{{ $gallery->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex items-center justify-between text-white">
                                        <div>
                                            <span class="text-sm font-medium">{{ $gallery->photos_count ?? $gallery->photos->count() }} photos</span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">{{ $gallery->name }}</h3>
                            @if($gallery->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($gallery->description, 100) }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $gallery->created_at->format('M j, Y') }}</span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $gallery->photos_count ?? $gallery->photos->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Link Overlay -->
                        <a href="{{ route('galleries.show', $gallery->slug) }}" class="absolute inset-0"></a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-center mt-12">
                {{ $galleries->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No galleries found</h3>
                    <p class="text-gray-600 mb-8">No public galleries are available at the moment. Check back later for new content!</p>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        @endif

        <!-- Shared with You Section -->
        @auth
            @if(isset($sharedGalleries) && $sharedGalleries->count() > 0)
                <div class="mt-20 border-t border-gray-200 pt-16">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Shared with You</h2>
                        <p class="text-lg text-gray-600">Galleries that have been shared with you privately</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($sharedGalleries as $gallery)
                            <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                                <!-- Shared Badge -->
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white shadow-lg">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                        Shared
                                    </span>
                                </div>

                                <!-- Gallery Image -->
                                <div class="aspect-w-16 aspect-h-10 relative overflow-hidden">
                                    @if($gallery->cover_image)
                                        <img src="{{ asset('storage/' . $gallery->cover_image) }}"
                                             alt="{{ $gallery->name }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Gallery Info -->
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $gallery->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">Shared by {{ $gallery->user->name }}</p>
                                    @if($gallery->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($gallery->description, 80) }}</p>
                                    @endif
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span>{{ $gallery->created_at->format('M j, Y') }}</span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $gallery->photos_count }} photos
                                        </span>
                                    </div>
                                </div>

                                <!-- Link Overlay -->
                                <a href="{{ route('galleries.show', $gallery->slug) }}" class="absolute inset-0"></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection

@section('scripts')
<script>
function galleriesApp() {
    return {
        // UI State
        layout: 'grid',
        searchQuery: '',
        sortBy: 'created_at_desc',
        dateFilter: '',
        photoCountFilter: '',

        // Initialize
        init() {
            // Load layout preference from localStorage
            const savedLayout = localStorage.getItem('gallery-layout');
            if (savedLayout) {
                this.layout = savedLayout;
            }
        },

        // Layout functionality
        setLayout(newLayout) {
            this.layout = newLayout;
            localStorage.setItem('gallery-layout', newLayout);
        },

        // Filter helpers
        hasActiveFilters() {
            return this.searchQuery || this.dateFilter || this.photoCountFilter || this.sortBy !== 'created_at_desc';
        },

        getActiveFilters() {
            const filters = [];

            if (this.searchQuery) {
                filters.push({ key: 'search', label: `Search: "${this.searchQuery}"` });
            }

            if (this.dateFilter) {
                const labels = {
                    today: 'Today',
                    week: 'This Week',
                    month: 'This Month',
                    year: 'This Year'
                };
                filters.push({ key: 'date', label: `Date: ${labels[this.dateFilter]}` });
            }

            if (this.photoCountFilter) {
                filters.push({ key: 'photos', label: `Photos: ${this.photoCountFilter}` });
            }

            if (this.sortBy !== 'created_at_desc') {
                const labels = {
                    'created_at_asc': 'Oldest First',
                    'name_asc': 'Name A-Z',
                    'name_desc': 'Name Z-A',
                    'photos_count_desc': 'Most Photos',
                    'photos_count_asc': 'Least Photos'
                };
                filters.push({ key: 'sort', label: `Sort: ${labels[this.sortBy]}` });
            }

            return filters;
        },

        removeFilter(key) {
            switch(key) {
                case 'search':
                    this.searchQuery = '';
                    break;
                case 'date':
                    this.dateFilter = '';
                    break;
                case 'photos':
                    this.photoCountFilter = '';
                    break;
                case 'sort':
                    this.sortBy = 'created_at_desc';
                    break;
            }
            // Here you would typically apply filters via AJAX
            // For now, we'll just update the form and submit
            this.applyFilters();
        },

        clearFilters() {
            this.searchQuery = '';
            this.dateFilter = '';
            this.photoCountFilter = '';
            this.sortBy = 'created_at_desc';
            this.applyFilters();
        },

        applyFilters() {
            // In a full implementation, this would make an AJAX request
            // For now, let's just show the filter changes
            console.log('Filters applied:', {
                search: this.searchQuery,
                sort: this.sortBy,
                date: this.dateFilter,
                photos: this.photoCountFilter
            });
        },

        performSearch() {
            // Debounced search - in full implementation would make AJAX call
            this.applyFilters();
        }
    }
}
</script>
@endsection
