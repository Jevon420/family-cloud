@extends('layouts.app')

@section('title', 'Shared With You')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Shared With You</h1>
                <div class="mt-2 flex items-center">
                    <span class="text-sm text-gray-600">
                        Shared by: <strong>{{ $sharedBy->name }}</strong>
                    </span>
                    @if($sharedMedia->expires_at)
                        <span class="ml-4 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Expires: {{ $sharedMedia->expires_at->format('F j, Y') }}
                        </span>
                    @endif
                </div>
                <div class="mt-1">
                    <span class="text-sm text-gray-600">
                        Permissions:
                        @foreach($permissions as $permission)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                                {{ ucfirst($permission) }}
                            </span>
                        @endforeach
                    </span>
                </div>
            </div>

            <div class="p-6">
                @if($media instanceof \App\Models\Gallery)
                    <!-- Gallery Display -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">{{ $media->name }}</h2>
                        @if($media->description)
                            <p class="mt-2 text-gray-600">{{ $media->description }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($media->photos as $photo)
                            <div class="aspect-w-1 aspect-h-1 overflow-hidden rounded-lg bg-gray-100">
                                <img src="{{ asset('storage/' . $photo->file_path) }}"
                                     alt="{{ $photo->name }}"
                                     class="object-cover cursor-pointer"
                                     onclick="openPhotoModal('{{ asset('storage/' . $photo->file_path) }}', '{{ $photo->name }}')">
                            </div>
                        @endforeach
                    </div>

                @elseif($media instanceof \App\Models\Photo)
                    <!-- Photo Display -->
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $media->file_path) }}"
                             alt="{{ $media->name }}"
                             class="max-w-full h-auto mx-auto rounded-lg shadow-lg">
                        <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ $media->name }}</h2>
                        @if($media->description)
                            <p class="mt-2 text-gray-600">{{ $media->description }}</p>
                        @endif
                    </div>

                @elseif($media instanceof \App\Models\File)
                    <!-- File Display -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-lg mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $media->name }}</h2>
                        @if($media->description)
                            <p class="mt-2 text-gray-600">{{ $media->description }}</p>
                        @endif

                        <div class="mt-4 space-y-2">
                            <p class="text-sm text-gray-500">
                                <span class="font-medium">Size:</span> {{ number_format($media->file_size / 1024, 2) }} KB
                            </p>
                            <p class="text-sm text-gray-500">
                                <span class="font-medium">Type:</span> {{ $media->mime_type }}
                            </p>
                        </div>

                        @if(in_array('download', $permissions))
                            <div class="mt-6">
                                <a href="{{ route('files.download', $media->id) }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        @endif
                    </div>

                @elseif($media instanceof \App\Models\Folder)
                    <!-- Folder Display -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">{{ $media->name }}</h2>
                        @if($media->description)
                            <p class="mt-2 text-gray-600">{{ $media->description }}</p>
                        @endif
                    </div>

                    @if($media->files->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Files</h3>
                            <div class="space-y-2">
                                @foreach($media->files as $file)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $file->name }}</p>
                                                <p class="text-sm text-gray-500">{{ number_format($file->file_size / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        @if(in_array('download', $permissions))
                                            <a href="{{ route('files.download', $file->id) }}"
                                               class="text-indigo-600 hover:text-indigo-500">
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($media->subfolders->count() > 0)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subfolders</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($media->subfolders as $subfolder)
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $subfolder->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $subfolder->files_count }} files</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
            <div class="absolute bottom-4 left-4 text-white">
                <p id="modalImageName" class="text-lg font-medium"></p>
            </div>
        </div>
    </div>
</div>

<script>
function openPhotoModal(src, name) {
    document.getElementById('modalImage').src = src;
    document.getElementById('modalImageName').textContent = name;
    document.getElementById('photoModal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
@endsection
