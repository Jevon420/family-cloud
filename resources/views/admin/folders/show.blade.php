@extends('admin.layouts.app')

@section('title', 'View Folder')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $folder->name }}</h1>
            <div class="text-sm text-gray-500">
                @if($folder->parent)
                <span>In: <a href="{{ route('admin.folders.show', $folder->parent) }}" class="text-indigo-600 hover:text-indigo-900">{{ $folder->parent->name }}</a></span>
                @else
                <span>Root Folder</span>
                @endif
            </div>
        </div>
        <div class="flex space-x-2">
            @include('family.partials.share-button', ['media' => $folder, 'mediaType' => 'folder', 'darkMode' => false])
            <a href="{{ route('admin.folders.edit', $folder) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Edit Folder
            </a>
            <a href="{{ route('admin.folders.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to Folders
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mb-6">
        <div class="md:flex">
            <div class="md:w-1/4 mb-4 md:mb-0">
                @if($folder->cover_image)
                <img src="{{ Storage::url($folder->cover_image) }}" alt="{{ $folder->name }}" class="h-48 w-full object-cover rounded">
                @else
                <div class="h-48 w-full bg-gray-200 flex items-center justify-center rounded">
                    <svg class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    </svg>
                </div>
                @endif
            </div>
            <div class="md:w-3/4 md:pl-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Description</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $folder->description ?: 'No description provided.' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Owner</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $folder->user->name }} ({{ $folder->user->email }})</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Visibility</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($folder->visibility === 'public')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Public
                                </span>
                            @elseif($folder->visibility === 'private')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Private
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Shared
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Created</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $folder->created_at->format('M d, Y \a\t H:i') }}</p>
                        <p class="text-xs text-gray-500">by {{ $folder->creator->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $folder->updated_at->format('M d, Y \a\t H:i') }}</p>
                        @if($folder->updater)
                        <p class="text-xs text-gray-500">by {{ $folder->updater->name }}</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tags</h3>
                        <div class="mt-1">
                            @forelse($folder->tags as $tag)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-1 mb-1">
                                {{ $tag->name }}
                            </span>
                            @empty
                            <p class="text-sm text-gray-500">No tags assigned</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subfolders -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Subfolders</h2>
            <a href="{{ route('admin.folders.create') }}?parent_id={{ $folder->id }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add Subfolder
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto overflow-scroll">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subfolders</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($subfolders as $subfolder)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($subfolder->cover_image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($subfolder->cover_image) }}" alt="{{ $subfolder->name }}">
                                        @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.folders.show', $subfolder) }}" class="hover:text-indigo-600">
                                                {{ $subfolder->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($subfolder->description, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $subfolder->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $subfolder->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $subfolder->children->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $subfolder->files->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($subfolder->visibility === 'public')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Public
                                    </span>
                                @elseif($subfolder->visibility === 'private')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Private
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Shared
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subfolder->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.folders.show', $subfolder) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <a href="{{ route('admin.folders.edit', $subfolder) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <form action="{{ route('admin.folders.destroy', $subfolder) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this folder?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No subfolders found. <a href="{{ route('admin.folders.create') }}?parent_id={{ $folder->id }}" class="text-indigo-600 hover:text-indigo-900">Create one now</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Files -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Files</h2>
            <a href="{{ route('admin.files.create') }}?folder_id={{ $folder->id }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add File
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto overflow-scroll">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($files as $file)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if(Str::startsWith($file->mime_type, 'image/'))
                                        <img class="h-10 w-10 rounded object-cover" src="{{ Storage::url($file->thumbnail_path) }}" alt="{{ $file->name }}">
                                        @else
                                        <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.files.show', $file) }}" class="hover:text-indigo-600">
                                                {{ $file->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($file->description, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $file->mime_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $file->formatted_size }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $file->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $file->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($file->visibility === 'public')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Public
                                    </span>
                                @elseif($file->visibility === 'private')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Private
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Shared
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $file->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.files.show', $file) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <a href="{{ route('admin.files.edit', $file) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <a href="{{ route('admin.files.download', $file) }}" class="text-green-600 hover:text-green-900 mr-3">Download</a>
                                <form action="{{ route('admin.files.destroy', $file) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No files found. <a href="{{ route('admin.files.create') }}?folder_id={{ $folder->id }}" class="text-indigo-600 hover:text-indigo-900">Upload one now</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('family.partials.sharing-modal', ['darkMode' => false])
@endsection
