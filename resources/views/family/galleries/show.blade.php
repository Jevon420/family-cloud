@extends('family.layouts.app')

@section('title', $gallery->name)

@push('styles')
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-2 sm:px-4">
    <!-- Adjusted layout for mobile view -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $gallery->name }}</h1>
            @if($gallery->description)
                <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">{{ $gallery->description }}</p>
            @endif
            <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">Created {{ $gallery->created_at->format('F j, Y') }} • {{ $photos->total() }} photos</p>
        </div>

        <!-- Options Icon for Mobile View -->
        <div x-data="{ showOptions: false }" class="relative w-full sm:w-auto">
            <button type="button" x-on:click="showOptions = !showOptions" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }} w-full sm:hidden">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd" />
                </svg>
                Options
            </button>

            <div x-show="showOptions" x-cloak class="absolute top-full left-0 w-full bg-white shadow-md rounded-md mt-2 z-10">
                <a href="{{ route('family.galleries.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Back to Galleries</a>
                @include('family.partials.share-button', ['media' => $gallery, 'mediaType' => 'gallery'])
                <button type="button" x-on:click="$dispatch('open-modal')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Photos</button>
                @if($photos->isNotEmpty())
                    <button type="button" onclick="openModal({{ $photos->first()->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Watch Gallery</button>
                @endif
            </div>

            <!-- Action Buttons for Desktop View -->
            <div class="hidden sm:flex flex-wrap space-x-2">
                <a href="{{ route('family.galleries.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Galleries
                </a>

                @include('family.partials.share-button', ['media' => $gallery, 'mediaType' => 'gallery'])

                <button type="button" x-data="{}" x-on:click="$dispatch('open-modal')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Photos
                </button>
                @if($photos->isNotEmpty())
                    <button type="button" onclick="openModal({{ $photos->first()->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Watch Gallery
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Layout Options -->
    <div class="mb-6 flex justify-end space-x-2">
        <button type="button" onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'grid']) }}'" class="px-3 py-1 rounded {{ $galleryLayout === 'grid' ? 'bg-indigo-600 text-white' : ($darkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300') }}">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 012 2v2a2 2 01-2 2h-2a2 2 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 012 2v2a2 2 01-2 2h-2a2 2 01-2-2v-2z" />
            </svg>
        </button>
        <button type="button" onclick="window.location.href='{{ request()->fullUrlWithQuery(['layout' => 'masonry']) }}'" class="px-3 py-1 rounded {{ $galleryLayout === 'masonry' ? 'bg-indigo-600 text-white' : ($darkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300') }}">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v4H5V5zm0 6h2v4H5v-4zm4 0h2v4H9v-4zm4 0h2v4h-2v-4z" />
            </svg>
        </button>
    </div>

    <!-- Search, Filter, Sort -->
    <div x-data="{ showSearchOptions: false }" class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <!-- Mobile Options Icon -->
        <button type="button" x-on:click="showSearchOptions = !showSearchOptions" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }} w-full sm:hidden">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd" />
            </svg>
            Search Options
        </button>

        <!-- Mobile Dropdown for Search Options -->
        <div x-show="showSearchOptions" x-cloak class="absolute top-full left-0 w-full bg-white shadow-md rounded-md mt-2 z-10">
            <form method="GET" action="{{ route('family.galleries.show', $gallery->slug) }}" class="flex flex-col space-y-2 px-4 py-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search photos..."
                       class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <select name="sort" id="sortSelect" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Sort By</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                </select>
                <select name="sort_order" id="sortOrder" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Search</button>
            </form>
            <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="block px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Reset</a>
        </div>

        <!-- Desktop View -->
        <form method="GET" action="{{ route('family.galleries.show', $gallery->slug) }}" class="hidden sm:flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search photos..."
                   class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <select name="sort" id="sortSelect" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Sort By</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
            </select>
            <select name="sort_order" id="sortOrder" class="px-4 py-2 border rounded-md shadow-sm text-sm {{ $darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800' }} focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Search</button>
        </form>
        <a href="{{ route('family.galleries.show', $gallery->slug) }}" class="hidden sm:block px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Reset</a>
    </div>



    @if($photos->isEmpty())
        <div class="text-center py-12 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-500' }} rounded-lg">
            <svg class="mx-auto h-12 w-12 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">No photos in this gallery</h3>
            <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">Get started by adding photos to this gallery.</p>
            <div class="mt-6">
                <button type="button" x-data="{}" x-on:click="$dispatch('open-modal')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Photos
                </button>
            </div>
        </div>
    @else
        @if($galleryLayout === 'grid')
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($photos as $photo)
                <div class="group relative">
                    <a href="#" class="block photo-trigger" data-photo-id="{{ $photo->id }}" data-photo-url="{{ $photo->signed_url }}" data-photo-title="{{ $photo->name }}" data-photo-date="{{ $photo->created_at->format('F j, Y') }}">
                        <div class="aspect-w-1 aspect-h-1 overflow-hidden rounded-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                            <img src="{{ $photo->signed_thumbnail_url }}" alt="{{ $photo->name }}" class="object-cover wasabi-monitored" onerror="this.src='{{ asset('images/placeholder.jpg') }}'; console.log('Image failed to load: {{ $photo->file_path }}');">
                            <div class="flex items-end p-2 opacity-0 group-hover:opacity-100" aria-hidden="true">
                                <div class="w-full rounded-md {{ $darkMode ? 'bg-gray-800 bg-opacity-75 text-white' : 'bg-white bg-opacity-75 text-gray-900' }} py-1 px-2 text-center text-xs font-medium backdrop-blur backdrop-filter">View Photo</div>
                            </div>
                            <div class="absolute top-0 right-0 p-1">
                                <a href="{{ route('family.photos.debug', $photo->id) }}" target="_blank" class="text-xs bg-blue-500 text-white px-2 py-1 rounded">Debug</a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h3 class="text-sm {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">{{ $photo->name }}</h3>
                            <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $photo->created_at->format('M j, Y') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4">
                @foreach($photos as $photo)
                <div class="mb-4 break-inside-avoid">
                    <a href="#" class="block photo-trigger" data-photo-id="{{ $photo->id }}" data-photo-url="{{ $photo->signed_url }}" data-photo-title="{{ $photo->name }}" data-photo-date="{{ $photo->created_at->format('F j, Y') }}">
                        <div class="overflow-hidden rounded-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                            <img src="{{ $photo->signed_thumbnail_url }}" alt="{{ $photo->name }}" class="w-full wasabi-monitored" onerror="this.src='{{ asset('images/placeholder.php') }}'; console.log('Image failed to load: {{ $photo->file_path }}');">
                            <div class="absolute top-0 right-0 p-1">
                                <a href="{{ route('family.photos.debug', $photo->id) }}" target="_blank" class="text-xs bg-blue-500 text-white px-2 py-1 rounded">Debug</a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h3 class="text-sm {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">{{ $photo->name }}</h3>
                            <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $photo->created_at->format('M j, Y') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif

        <div class="mt-6">
            {{ $photos->links() }}
        </div>
    @endif

    <!-- Photo Upload Modal -->
    <div
        x-data="{ open: false }"
        x-on:open-modal.window="open = true"
        x-on:close-modal.window="open = false"
        x-show="open"
        class="fixed inset-0 overflow-y-auto z-50"
        x-cloak
    >
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 {{ $darkMode ? 'bg-gray-900' : 'bg-gray-500' }} opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}" id="modal-title">
                                Upload Photos to Gallery
                            </h3>
                            <div class="mt-4">
                                <form action="{{ route('family.galleries.photos.upload', $gallery->slug) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="photos" class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Select Photos</label>
                                        <div class="mt-1">
                                            <input type="file" id="photos" name="photos[]" multiple accept="image/*" required class="{{ $darkMode ? 'bg-gray-700 text-white' : 'bg-white text-gray-900' }} relative block w-full px-3 py-2 border {{ $darkMode ? 'border-gray-600' : 'border-gray-300' }} placeholder-gray-500 text-sm rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10">
                                        </div>
                                        <p class="mt-1 text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">You can select multiple photos. Max 5MB per photo.</p>
                                    </div>

                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                            Upload
                                        </button>
                                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border {{ $darkMode ? 'border-gray-600 bg-gray-700 text-gray-300' : 'border-gray-300 bg-white text-gray-700' }} shadow-sm px-4 py-2 text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Carousel Modal -->
    <div id="photoCarouselModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-700 bg-opacity-75 backdrop-blur-sm">
        <div class="relative w-full h-full flex flex-col">
            <!-- Close Button -->
            <button id="closeCarouselModal" class="absolute top-4 right-4 z-10 bg-red-500 text-white rounded-full p-3 hover:bg-red-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Main Photo Display -->
            <div class="flex-1 flex items-center justify-center p-4">
                <div class="relative max-w-4xl max-h-full">
                    <!-- Navigation Buttons -->
                    <button id="prevPhotoBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-3 hover:bg-opacity-70 transition-all z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button id="nextPhotoBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-3 hover:bg-opacity-70 transition-all z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Photo Container -->
                    <div class="relative">
                        <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl wasabi-monitored">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white p-3 rounded-lg">
                            <h3 id="modalPhotoTitle" class="text-lg font-bold"></h3>
                            <p id="modalPhotoDate" class="text-sm opacity-75"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Strip -->
            <div class="bg-black bg-opacity-50 backdrop-blur-sm p-4">
                <div class="flex space-x-2 overflow-x-auto scrollbar-hide" id="photoThumbnails">
                    <!-- Thumbnails will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

</div>


@push('scripts')
<script>
    const photoData = <?php echo json_encode($photos->map(function($photo) {
        return [
            'id' => $photo->id,
            'url' => route('admin.storage.signedUrl', ['path' => $photo->file_path, 'type' => 'long']),
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

    // Generate thumbnails
    function generateThumbnails() {
        thumbnailContainer.innerHTML = '';
        photoData.forEach((photo, index) => {
            const thumbnail = document.createElement('div');
            thumbnail.className = `flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden cursor-pointer transition-all duration-300 ${index === currentPhotoIndex ? 'ring-4 ring-blue-500 scale-110' : 'opacity-70 hover:opacity-100'}`;
            thumbnail.innerHTML = `<img src="${photo.url}" alt="${photo.title}" class="w-full h-full object-cover wasabi-monitored">`;
            thumbnail.onclick = () => showPhoto(index);
            thumbnailContainer.appendChild(thumbnail);
        });

        // Center thumbnails
        thumbnailContainer.style.justifyContent = 'center';
    }

    // Show photo at index
    function showPhoto(index) {
        if (index < 0 || index >= photoData.length) return;

        currentPhotoIndex = index;
        const photo = photoData[index];

        modalPhoto.src = photo.url;
        modalPhoto.alt = photo.title;
        modalPhotoTitle.textContent = photo.title;
        modalPhotoDate.textContent = photo.date;

        // Update thumbnails
        generateThumbnails();

        // Scroll to current thumbnail
        const activeThumbnail = thumbnailContainer.children[index];
        if (activeThumbnail) {
            activeThumbnail.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Open modal
    function openModal(photoId) {
        const photoIndex = photoData.findIndex(photo => photo.id == photoId);
        if (photoIndex !== -1) {
            showPhoto(photoIndex);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Event listeners
    document.getElementById('closeCarouselModal').onclick = closeModal;
    document.getElementById('prevPhotoBtn').onclick = () => showPhoto(currentPhotoIndex - 1);
    document.getElementById('nextPhotoBtn').onclick = () => showPhoto(currentPhotoIndex + 1);

    // Photo trigger clicks
    document.querySelectorAll('.photo-trigger').forEach(trigger => {
        trigger.onclick = (e) => {
            e.preventDefault();
            const photoId = trigger.dataset.photoId;
            openModal(photoId);
        };
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape':
                    closeModal();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    showPhoto(currentPhotoIndex - 1);
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    showPhoto(currentPhotoIndex + 1);
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
        handleSwipe();
    });

    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            // Swipe left - next photo
            showPhoto(currentPhotoIndex + 1);
        }
        if (touchEndX > touchStartX + 50) {
            // Swipe right - previous photo
            showPhoto(currentPhotoIndex - 1);
        }
    }
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('photoUpload', () => ({
            open: false,
            showModal() {
                this.open = true;
            },
            hideModal() {
                this.open = false;
            }
        }));
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const container = document.querySelector('.container');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('family.galleries.show', $gallery->slug) }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
        });

        const resetButton = document.querySelector('a[href="{{ route('family.galleries.show', $gallery->slug) }}"]');

        resetButton.addEventListener('click', function (event) {
            event.preventDefault();

            form.reset();

            // Clear query parameters
            const url = `{{ route('family.galleries.show', $gallery->slug) }}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchOptions', () => ({
            showSearchOptions: false
        }));
    });
</script>

@include('family.partials.sharing-modal')
@endpush
@endsection
