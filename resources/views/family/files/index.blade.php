@extends('family.layouts.app')

@section('title', 'My Files')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold {{ $darkMode ? 'text-white' : 'text-gray-900' }}">My Files</h1>
            @if($currentFolder)
                <div class="mt-1 flex items-center text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">
                    <a href="{{ route('family.files.index') }}" class="{{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }}">Root</a>
                    @php
                        $ancestors = [];
                        $parent = $currentFolder;
                        while($parent->parent_id) {
                            $parent = \App\Models\Folder::find($parent->parent_id);
                            $ancestors[] = $parent;
                        }
                        $ancestors = array_reverse($ancestors);
                    @endphp

                    @foreach($ancestors as $ancestor)
                        <svg class="mx-1 h-5 w-5 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <a href="{{ route('family.files.index', ['folder_id' => $ancestor->id]) }}" class="{{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }}">{{ $ancestor->name }}</a>
                    @endforeach

                    <svg class="mx-1 h-5 w-5 {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="{{ $darkMode ? 'text-gray-300' : 'text-gray-600' }}">{{ $currentFolder->name }}</span>
                </div>
            @endif
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('family.folders.create', ['parent_id' => $currentFolder->id ?? null]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                    <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                </svg>
                New Folder
            </a>
            <a href="{{ route('family.files.create', ['folder_id' => $currentFolder->id ?? null]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Upload File
            </a>
        </div>
    </div>

    @if($folders->isEmpty() && $files->isEmpty())
        <div class="text-center py-12 {{ $darkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-500' }} rounded-lg">
            <svg class="mx-auto h-12 w-12 {{ $darkMode ? 'text-gray-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">No files or folders</h3>
            <p class="mt-1 text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">Get started by creating a folder or uploading a file.</p>
            <div class="mt-6 space-x-2">
                <a href="{{ route('family.folders.create', ['parent_id' => $currentFolder->id ?? null]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm {{ $darkMode ? 'bg-gray-600 text-white hover:bg-gray-500' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                        <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                    </svg>
                    New Folder
                </a>
                <a href="{{ route('family.files.create', ['folder_id' => $currentFolder->id ?? null]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Upload File
                </a>
            </div>
        </div>
    @else
        <!-- Folders -->
        @if($folders->isNotEmpty())
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-3 {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Folders</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($folders as $folder)
                    <a href="{{ route('family.files.index', ['folder_id' => $folder->id]) }}" class="flex items-center p-4 {{ $darkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-white hover:bg-gray-50' }} rounded-lg shadow">
                        <svg class="h-10 w-10 {{ $darkMode ? 'text-indigo-300' : 'text-indigo-500' }} mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <div class="overflow-hidden">
                            <h3 class="font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} truncate">{{ $folder->name }}</h3>
                            @if($folder->description)
                            <p class="text-xs {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} truncate">{{ $folder->description }}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Files -->
        @if($files->isNotEmpty())
            <div>
                <h2 class="text-lg font-semibold mb-3 {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Files</h2>
                <div class="overflow-hidden shadow ring-1 {{ $darkMode ? 'ring-gray-700' : 'ring-black ring-opacity-5' }} sm:rounded-lg">
                    <table class="min-w-full divide-y {{ $darkMode ? 'divide-gray-600' : 'divide-gray-300' }}">
                        <thead class="{{ $darkMode ? 'bg-gray-700' : 'bg-gray-50' }}">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Size</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }} uppercase tracking-wider">Added</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="{{ $darkMode ? 'bg-gray-800 divide-y divide-gray-700' : 'bg-white divide-y divide-gray-200' }}">
                            @foreach($files as $file)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }} rounded">
                                            @php
                                                $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
                                                $iconColor = $darkMode ? 'text-indigo-300' : 'text-indigo-600';
                                            @endphp

                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif(in_array($extension, ['doc', 'docx', 'txt', 'pdf']))
                                                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif(in_array($extension, ['mp3', 'wav', 'ogg']))
                                                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif(in_array($extension, ['mp4', 'avi', 'mov', 'wmv']))
                                                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                                </svg>
                                            @else
                                                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('family.files.show', $file->id) }}" class="text-sm font-medium {{ $darkMode ? 'text-white hover:text-indigo-300' : 'text-gray-900 hover:text-indigo-600' }}">{{ $file->title }}</a>
                                            <div class="text-xs {{ $darkMode ? 'text-gray-400' : 'text-gray-500' }}">{{ $file->filename }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ round($file->file_size / 1024, 2) }} KB</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ Str::upper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-500' }}">{{ $file->created_at->format('F j, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('family.files.download', $file->id) }}" class="{{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }} mr-3">Download</a>
                                    <a href="{{ route('family.files.show', $file->id) }}" class="{{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-900' }}">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $files->links() }}
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
