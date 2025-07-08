@extends('layouts.app')

@section('title', $gallery->name)
@section('meta_description', $gallery->description ?? 'View photos in ' . $gallery->name)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
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
                        <a href="{{ route('galleries.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Galleries</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $gallery->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Gallery Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $gallery->name }}</h1>
                    @if($gallery->description)
                        <p class="mt-2 text-lg text-gray-600">{{ $gallery->description }}</p>
                    @endif
                    <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $photos->total() }} photos</span>
                        <span>•</span>
                        <span>Created {{ $gallery->created_at->format('F j, Y') }}</span>
                        @if($gallery->updated_at->ne($gallery->created_at))
                            <span>•</span>
                            <span>Updated {{ $gallery->updated_at->format('F j, Y') }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2">
                    @php $darkMode = false; @endphp
                    @include('family.partials.share-button', ['media' => $gallery, 'mediaType' => 'gallery'])
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download All
                    </button>
                </div>
            </div>
        </div>

        <!-- Photos Grid -->
        @if($photos->count() > 0)
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($photos as $photo)
                    <div class="group relative bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="aspect-w-1 aspect-h-1">
                            <img class="w-full h-64 object-cover group-hover:opacity-75 transition-opacity duration-200"
                                 src="{{ $photo->thumbnail_url ?? $photo->url }}"
                                 alt="{{ $photo->title ?? 'Photo' }}"
                                 loading="lazy">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <div class="flex items-center justify-between">
                                    @if($photo->title)
                                        <h3 class="text-white font-medium text-sm">{{ $photo->title }}</h3>
                                    @endif
                                    <div class="flex space-x-2">
                                        <a href="{{ route('photos.show', $photo->id) }}" class="text-white hover:text-gray-200">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button class="text-white hover:text-gray-200">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('photos.show', $photo->id) }}" class="absolute inset-0"></a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $photos->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No photos in this gallery</h3>
                <p class="mt-1 text-sm text-gray-500">This gallery doesn't have any photos yet.</p>
            </div>
        @endif
    </div>
</div>

@include('family.partials.sharing-modal', ['darkMode' => false])
@endsection
