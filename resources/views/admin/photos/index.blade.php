@extends('admin.layouts.app')

@section('title', 'Photo Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Photo Management</h1>
        <a href="{{ route('admin.photos.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Upload New Photo
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto overflow-scroll">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gallery</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($photos as $photo)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($photo->thumbnail_path)
                                <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($photo->thumbnail_path) }}" alt="{{ $photo->name }}">
                                @elseif($photo->file_path)
                                <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->name }}">
                                @else
                                <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('admin.photos.show', $photo) }}" class="hover:text-indigo-600">
                                    {{ $photo->name }}
                                </a>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($photo->description, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $photo->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $photo->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($photo->gallery)
                            <a href="{{ route('admin.galleries.show', $photo->gallery) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                {{ $photo->gallery->name }}
                            </a>
                            @else
                            <span class="text-sm text-gray-500">No Gallery</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($photo->file_size / 1024, 2) }} KB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($photo->visibility === 'public')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Public
                                </span>
                            @elseif($photo->visibility === 'private')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Private
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Shared
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.photos.show', $photo) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                <a href="{{ route('admin.photos.edit', $photo) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this photo?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No photos found. <a href="{{ route('admin.photos.create') }}" class="text-indigo-600 hover:text-indigo-900">Upload one now</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $photos->links() }}
    </div>
</div>
@endsection
