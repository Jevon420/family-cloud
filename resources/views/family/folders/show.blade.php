@extends('family.layouts.app')

@section('title', $folder->name)

@section('content')
<div class="container px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            @if($folder->parent_id)
                <a href="{{ route('family.folders.show', $folder->parent_id) }}" class="mr-2 text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <a href="{{ route('family.folders.index') }}" class="mr-2 text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
            <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                {{ $folder->name }}
            </h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('family.files.create', ['folder_id' => $folder->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Upload File
            </a>
            <a href="{{ route('family.folders.create', ['parent_id' => $folder->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                New Folder
            </a>
        </div>
    </div>

    @if($folder->description)
        <div class="bg-white shadow-sm rounded-lg p-4 mb-6 {{ $darkMode ? 'bg-gray-800 text-gray-300' : '' }}">
            <p>{{ $folder->description }}</p>
        </div>
    @endif

    @if($folder->subfolders->isEmpty() && $folder->files->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 {{ $darkMode ? 'bg-gray-800 text-white' : '' }}">
            <p class="text-center text-gray-500 {{ $darkMode ? 'text-gray-400' : '' }}">This folder is empty.</p>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
            @if($folder->subfolders->isNotEmpty())
                <div class="border-b {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }}">
                    <h2 class="text-lg font-medium p-4 {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Folders</h2>
                </div>
                <ul class="divide-y divide-gray-200 {{ $darkMode ? 'divide-gray-700' : '' }}">
                    @foreach($folder->subfolders as $subfolder)
                        <li class="flex items-center py-4 px-6 hover:bg-gray-50 {{ $darkMode ? 'hover:bg-gray-700' : '' }}">
                            <div class="flex-shrink-0 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('family.folders.show', $subfolder->id) }}"
                                   class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} hover:underline">
                                    {{ $subfolder->name }}
                                </a>
                                @if($subfolder->description)
                                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                        {{ Str::limit($subfolder->description, 100) }}
                                    </p>
                                @endif
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    Created: {{ $subfolder->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($folder->files->isNotEmpty())
                <div class="border-b {{ $darkMode ? 'border-gray-700' : 'border-gray-200' }}">
                    <h2 class="text-lg font-medium p-4 {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Files</h2>
                </div>
                <ul class="divide-y divide-gray-200 {{ $darkMode ? 'divide-gray-700' : '' }}">
                    @foreach($folder->files as $file)
                        <li class="flex items-center py-4 px-6 hover:bg-gray-50 {{ $darkMode ? 'hover:bg-gray-700' : '' }}">
                            <div class="flex-shrink-0 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('family.files.show', $file->id) }}"
                                   class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} hover:underline">
                                    {{ $file->name }}
                                </a>
                                @if($file->description)
                                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                        {{ Str::limit($file->description, 100) }}
                                    </p>
                                @endif
                                <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">
                                    Size: {{ $file->formatSize() }} | Added: {{ $file->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <a href="{{ route('family.files.download', $file->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
</div>
@endsection
