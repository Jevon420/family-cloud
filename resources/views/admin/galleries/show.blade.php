@extends('admin.layouts.app')

@section('title', $gallery->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $gallery->name }}</h1>
        <div>
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mr-2">
                Edit Gallery
            </a>
            <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to Galleries
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="md:flex">
            <div class="md:flex-shrink-0">
                @if($gallery->cover_image)
                <img class="h-48 w-full object-cover md:w-48" src="{{ Storage::url($gallery->cover_image) }}" alt="{{ $gallery->name }}">
                @else
                <div class="h-48 w-full bg-gray-200 flex items-center justify-center md:w-48">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                @endif
            </div>
            <div class="p-6">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Gallery Details</div>
                <p class="mt-2 text-gray-600">{{ $gallery->description }}</p>
                
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Owner</h3>
                        <p class="mt-1">{{ $gallery->user->name }} ({{ $gallery->user->email }})</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Visibility</h3>
                        <p class="mt-1">
                            @if($gallery->visibility === 'public')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Public
                                </span>
                            @elseif($gallery->visibility === 'private')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Private
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Shared
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Created By</h3>
                        <p class="mt-1">{{ $gallery->creator->name ?? 'Unknown' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                        <p class="mt-1">{{ $gallery->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500">Tags</h3>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @forelse($gallery->tags as $tag)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $tag->name }}
                            </span>
                            @empty
                            <span class="text-gray-500">No tags</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Photos ({{ $gallery->photos->count() }})</h2>
        <a href="{{ route('admin.photos.create', ['gallery_id' => $gallery->id]) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add Photos
        </a>
    </div>
    
    @if($gallery->photos->isEmpty())
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 text-center">
        <p class="text-gray-500">No photos in this gallery yet.</p>
        <a href="{{ route('admin.photos.create', ['gallery_id' => $gallery->id]) }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-900">Add photos now</a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($gallery->photos as $photo)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <a href="{{ route('admin.photos.show', $photo) }}" class="block">
                <img src="{{ Storage::url($photo->thumbnail_path ?? $photo->file_path) }}" alt="{{ $photo->name }}" class="w-full h-48 object-cover">
            </a>
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $photo->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($photo->description, 50) }}</p>
                <div class="mt-3 flex justify-between">
                    <a href="{{ route('admin.photos.show', $photo) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                    <a href="{{ route('admin.photos.edit', $photo) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                    <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this photo?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
