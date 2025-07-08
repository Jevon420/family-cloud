@extends('admin.layouts.app')

@section('title', $photo->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $photo->name }}</h1>
        <div>
            <a href="{{ route('admin.photos.edit', $photo) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mr-2">
                Edit Photo
            </a>
            @php $darkMode = false; @endphp
            @include('family.partials.share-button', ['media' => $photo, 'mediaType' => 'photo'])
            <a href="{{ route('admin.photos.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 ml-2">
                Back to Photos
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Image Preview</h2>

                <div>
                    <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 bg-white px-3 py-1 rounded-md text-sm shadow-sm" onclick="return confirm('Are you sure you want to delete this photo?')">Delete Photo</button>
                    </form>
                </div>
            </div>
            <div class="p-6 flex justify-center">
                <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->name }}" class="max-w-full max-h-96 object-contain">
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Photo Details</h2>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $photo->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $photo->description ?: 'No description' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Owner</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $photo->user->name }} ({{ $photo->user->email }})</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gallery</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($photo->gallery)
                            <a href="{{ route('admin.galleries.show', $photo->gallery) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $photo->gallery->name }}
                            </a>
                            @else
                            No Gallery
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Visibility</dt>
                        <dd class="mt-1">
                            @if($photo->visibility === 'public')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Public
                                </span>
                            @elseif($photo->visibility === 'private')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Private
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Shared
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">File Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($photo->file_size / 1024, 2) }} KB</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">MIME Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $photo->mime_type }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $photo->creator->name ?? 'Unknown' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $photo->created_at->format('M d, Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tags</dt>
                        <dd class="mt-1">
                            <div class="flex flex-wrap gap-2">
                                @forelse($photo->tags as $tag)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $tag->name }}
                                </span>
                                @empty
                                <span class="text-sm text-gray-500">No tags</span>
                                @endforelse
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
