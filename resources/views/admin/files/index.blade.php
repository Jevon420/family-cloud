@extends('admin.layouts.app')

@section('title', 'File Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">File Management</h1>
        <a href="{{ route('admin.files.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Upload New File
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folder</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($files as $file)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if(strpos($file->mime_type, 'image/') === 0 && $file->thumbnail_path)
                                <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($file->thumbnail_path) }}" alt="{{ $file->name }}">
                                @else
                                <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('admin.files.show', $file) }}" class="hover:text-indigo-600">
                                    {{ $file->name }}
                                </a>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($file->description, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $file->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $file->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($file->folder)
                            <a href="{{ route('admin.folders.show', $file->folder) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                {{ $file->folder->name }}
                            </a>
                            @else
                            <span class="text-sm text-gray-500">Root</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $file->mime_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($file->file_size / 1024, 2) }} KB
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.files.show', $file) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                <a href="{{ route('admin.files.edit', $file) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                <form action="{{ route('admin.files.destroy', $file) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No files found. <a href="{{ route('admin.files.create') }}" class="text-indigo-600 hover:text-indigo-900">Upload one now</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $files->links() }}
    </div>
</div>
@endsection
