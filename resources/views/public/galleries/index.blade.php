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
        @endif
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
