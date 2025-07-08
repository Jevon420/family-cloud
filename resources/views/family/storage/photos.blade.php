@extends('family.layouts.app')

@section('title', 'Photo Storage Details')

@section('content')
<div class="container mx-auto px-4 md:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Photo Storage Details</h1>
            <p class="mt-2 text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                Total: {{ number_format($totalSize / (1024 * 1024), 2) }} MB across {{ $photos->total() }} photos
            </p>
        </div>
        <a href="{{ route('family.storage.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Storage
        </a>
    </div>

    <!-- Photo Types Summary -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-4">Photo Types Breakdown</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($photoTypes as $type)
            <div class="p-4 {{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">{{ $type->mime_type ?: 'Unknown' }}</h3>
                    <span class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $type->count }} photos</span>
                </div>
                <div class="text-lg font-bold text-purple-500">{{ number_format($type->total_size / (1024 * 1024), 2) }} MB</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $totalSize > 0 ? ($type->total_size / $totalSize) * 100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="{{ $darkMode ? 'bg-gray-800' : 'bg-white' }} rounded-lg shadow">
        <div class="px-6 py-4 border-b {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }}">
            <h2 class="text-lg font-semibold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">All Photos (Sorted by Size)</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                <div class="group relative">
                    <div class="aspect-w-1 aspect-h-1 overflow-hidden rounded-lg {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->name }}" class="object-cover group-hover:opacity-75">
                        <div class="absolute inset-0 flex items-end p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-full rounded-md {{ $darkMode ? 'bg-gray-800 bg-opacity-75 text-white' : 'bg-white bg-opacity-75 text-gray-900' }} py-2 px-4 text-center text-xs font-medium backdrop-blur backdrop-filter">
                                {{ number_format($photo->file_size / 1024, 2) }} KB
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-sm {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">{{ $photo->name }}</h3>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                {{ $photo->mime_type ?: 'Unknown' }}
                            </span>
                            <span class="text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">
                                {{ number_format($photo->file_size / 1024, 2) }} KB
                            </span>
                        </div>
                        <p class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $photo->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="px-6 py-4 border-t {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }}">
            {{ $photos->links() }}
        </div>
    </div>
</div>
@endsection
