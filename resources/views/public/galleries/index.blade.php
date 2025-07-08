@extends('layouts.app')

@section('title', 'Galleries')
@section('meta_description', 'Browse our collection of family photo galleries')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Photo Galleries
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Explore our beautiful collection of family memories and special moments
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mt-8 max-w-md mx-auto">
            <div class="relative">
                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search galleries...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Galleries Grid -->
        @if($galleries->count() > 0)
            <div id="galleries-container">
                @include('public.galleries.partials.gallery-grid')
            </div>

            <!-- Load More Button -->
            <div class="flex justify-center mt-12">
                <button id="load-more" class="px-6 py-3 text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-next-page="{{ $galleries->nextPageUrl() }}" style="{{ !$galleries->hasMorePages() ? 'display: none' : '' }}">
                    <span>Load More</span>
                    <svg id="loading-spinner" class="animate-spin ml-2 h-5 w-5 text-white" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No galleries</h3>
                <p class="mt-1 text-sm text-gray-500">No public galleries are available at the moment.</p>
            </div>
        @endif

        <!-- Shared with You Section -->
        @auth
            @if(isset($sharedGalleries) && $sharedGalleries->count() > 0)
                <div class="mt-16 border-t border-gray-200 pt-16">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Shared with You</h2>
                        <p class="mt-2 text-gray-600">Galleries that have been shared with you privately</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @foreach($sharedGalleries as $gallery)
                            <div class="group relative">
                                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                    @if($gallery->cover_image)
                                        <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <h3 class="text-sm text-gray-700">
                                            <a href="{{ route('galleries.show', $gallery->slug) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $gallery->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-xs text-gray-500">
                                            Shared by {{ $gallery->user->name }}
                                        </p>
                                        @if($gallery->description)
                                            <p class="mt-1 text-xs text-gray-500">{{ Str::limit($gallery->description, 60) }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $gallery->photos_count }} photos</p>
                                        <p class="text-xs text-gray-500">{{ $gallery->created_at->format('M j, Y') }}</p>
                                    </div>
                                </div>

                                <!-- Shared indicator -->
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                        Shared
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2h10a2 2 0 012 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No galleries yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first gallery.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('load-more');
        const galleriesContainer = document.getElementById('galleries-container');
        const loadingSpinner = document.getElementById('loading-spinner');

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const nextPage = loadMoreBtn.dataset.nextPage;

                if (!nextPage) {
                    loadMoreBtn.style.display = 'none';
                    return;
                }

                // Show loading state
                loadMoreBtn.disabled = true;
                loadMoreBtn.querySelector('span').textContent = 'Loading...';
                loadingSpinner.style.display = 'inline-block';

                // Fetch next page
                fetch(nextPage, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Append new content
                    galleriesContainer.innerHTML += data.html;

                    // Update or hide the load more button
                    if (data.hasMorePages) {
                        const url = new URL(nextPage);
                        const page = parseInt(url.searchParams.get('page') || 1) + 1;
                        url.searchParams.set('page', page);
                        loadMoreBtn.dataset.nextPage = url.toString();
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Reset loading state
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.querySelector('span').textContent = 'Load More';
                    loadingSpinner.style.display = 'none';
                });
            });
        }
    });
</script>
@endsection
