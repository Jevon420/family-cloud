@extends('admin.layouts.app')

@section('title', $file->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $file->name }}</h1>
        <div>
            <a href="{{ route('admin.files.download', $file) }}" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                Download File
            </a>
            <a href="{{ route('admin.files.edit', $file) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mr-2">
                Edit File
            </a>
            @php $darkMode = false; @endphp
            @include('family.partials.share-button', ['media' => $file, 'mediaType' => 'file'])
            <a href="{{ route('admin.files.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 ml-2">
                Back to Files
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
                <h2 class="text-lg font-semibold text-gray-900">File Preview</h2>

                <div>
                    <form action="{{ route('admin.files.destroy', $file) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 bg-white px-3 py-1 rounded-md text-sm shadow-sm" onclick="return confirm('Are you sure you want to delete this file?')">Delete File</button>
                    </form>
                </div>
            </div>
            <div class="p-6">
                @if(strpos($file->mime_type, 'image/') === 0)
                <div class="flex justify-center">
                    <img src="{{ Storage::url($file->file_path) }}" alt="{{ $file->name }}" class="max-w-full max-h-96 object-contain">
                </div>
                @elseif(strpos($file->mime_type, 'video/') === 0)
                <div class="flex justify-center">
                    <video controls class="max-w-full max-h-96">
                        <source src="{{ Storage::url($file->file_path) }}" type="{{ $file->mime_type }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
                @elseif(strpos($file->mime_type, 'audio/') === 0)
                <div class="flex justify-center">
                    <audio controls class="w-full">
                        <source src="{{ Storage::url($file->file_path) }}" type="{{ $file->mime_type }}">
                        Your browser does not support the audio tag.
                    </audio>
                </div>
                @elseif($file->mime_type === 'application/pdf')
                <div class="flex justify-center">
                    <embed src="{{ Storage::url($file->file_path) }}" type="application/pdf" width="100%" height="500px">
                </div>
                @else
                <div class="flex flex-col items-center justify-center p-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4 text-lg text-gray-600">{{ $file->name }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ $file->mime_type }}</p>
                    <a href="{{ route('admin.files.download', $file) }}" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Download to View
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">File Details</h2>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $file->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $file->description ?: 'No description' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Owner</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $file->user->name }} ({{ $file->user->email }})</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Folder</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($file->folder)
                            <a href="{{ route('admin.folders.show', $file->folder) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $file->folder->name }}
                            </a>
                            @else
                            Root (No Folder)
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Visibility</dt>
                        <dd class="mt-1">
                            @if($file->visibility === 'public')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Public
                                </span>
                            @elseif($file->visibility === 'private')
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
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($file->file_size / 1024, 2) }} KB</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">MIME Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $file->mime_type }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $file->creator->name ?? 'Unknown' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $file->created_at->format('M d, Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tags</dt>
                        <dd class="mt-1">
                            <div class="flex flex-wrap gap-2">
                                @forelse($file->tags as $tag)
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
