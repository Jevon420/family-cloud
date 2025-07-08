@extends('family.layouts.app')

@section('title', 'Gallery Storage Details')

@section('content')
<div class="container mx-auto px-4 md:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Gallery Storage Details</h1>
            <p class="mt-2 text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                Storage usage breakdown by gallery
            </p>
        </div>
        <a href="{{ route('family.storage.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Storage
        </a>
    </div>

    <!-- Galleries List -->
    <div class="space-y-6">
        @foreach($galleries as $gallery)
        @php
            $gallerySize = $gallery->photos->sum('file_size') ?? 0;
        @endphp
        <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($gallery->cover_image)
                                <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->name }}" class="h-16 w-16 object-cover rounded-lg">
                            @else
                                <div class="h-16 w-16 {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                                    <svg class="h-8 w-8 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                                <a href="{{ route('family.galleries.show', $gallery->slug) }}">{{ $gallery->name }}</a>
                            </h3>
                            <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                {{ $gallery->photos_count }} photos • Created {{ $gallery->created_at->format('M j, Y') }}
                            </p>
                            @if($gallery->description)
                                <p class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }} mt-1">{{ $gallery->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                            {{ number_format($gallerySize / (1024 * 1024), 2) }} MB
                        </div>
                        <div class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                            {{ number_format($gallerySize / ($gallery->photos_count ?: 1) / 1024, 2) }} KB avg
                        </div>
                    </div>
                </div>

                @if($gallery->photos_count > 0)
                <!-- Photo Size Distribution -->
                <div class="mt-4">
                    <h4 class="text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-2">Photo Size Distribution</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($gallery->photos->sortByDesc('file_size')->take(6) as $photo)
                        <div class="flex items-center space-x-3 p-3 {{ $darkMode ? 'bg-gray-700' : 'bg-gray-50' }} rounded-lg">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->name }}" class="h-10 w-10 object-cover rounded">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">{{ $photo->name }}</p>
                                <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    {{ number_format($photo->file_size / 1024, 2) }} KB
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $galleries->links() }}
    </div>
</div>
@endsection
