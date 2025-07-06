@extends('layouts.app')

@section('title', 'Photos')
@section('meta_description', 'Browse our collection of family photos')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                All Photos
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Browse through our entire collection of family memories
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
            <div class="relative flex-1">
                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search photos...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                <option>All Photos</option>
                <option>Recent</option>
                <option>Oldest</option>
                <option>Most Popular</option>
            </select>
        </div>

        <!-- Photos Grid -->
        @if($photos->count() > 0)
            <div id="photos-container" class="mt-12">
                @include('public.photos.partials.photo-grid')
            </div>

            <!-- Load More Button -->
            <div class="flex justify-center mt-12">
                <button id="load-more" class="px-6 py-3 text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-next-page="{{ $photos->nextPageUrl() }}" style="{{ !$photos->hasMorePages() ? 'display: none' : '' }}">
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">No photos found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no public photos available at the moment.</p>
                <div class="mt-6">
                    <a href="{{ route('galleries.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2h10a2 2 0 012 2z" />
                        </svg>
                        Browse Galleries
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('load-more');
        const photosContainer = document.getElementById('photos-container');
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
                    photosContainer.innerHTML += data.html;

                    // Update or hide the load more button
                    if (data.hasMorePages) {
                        const url = new URL(nextPage);
                        const page = parseInt(url.searchParams.get('page') || 1) + 1;
                        url.searchParams.set('page', page);
                        loadMoreBtn.dataset.nextPage = url.toString();
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }

                    // Reinitialize photo click handlers
                    initPhotoLinks();
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

        // Initialize photo links for AJAX loading
        function initPhotoLinks() {
            const photoLinks = document.querySelectorAll('.photo-link');
            photoLinks.forEach(link => {
                if (!link.hasAttribute('data-initialized')) {
                    link.setAttribute('data-initialized', 'true');
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const photoId = this.getAttribute('data-photo-id');
                        if (photoId) {
                            history.pushState({}, '', this.href);
                            loadPhotoDetail(photoId);
                        }
                    });
                }
            });
        }

        // Load photo detail via AJAX
        function loadPhotoDetail(photoId) {
            // Implementation for the photo detail view will be added later
            // This function should fetch the photo detail and update the view
        }

        // Initialize photo links on page load
        initPhotoLinks();
    });
</script>
@endsection
