@extends('layouts.app')

@section('title', $photo->title ?? 'Photo')
@section('meta_description', $photo->description ?? 'View photo from Family Cloud')

@section('content')
<div class="bg-black min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation -->
        <div class="flex items-center justify-between mb-6">
            <nav class="flex items-center space-x-2">
                <a href="{{ route('photos.index') }}" class="text-gray-300 hover:text-white flex items-center">
                    <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Photos
                </a>
                @if($photo->gallery)
                    <span class="text-gray-500">•</span>
                    <a href="{{ route('galleries.show', $photo->gallery->slug) }}" class="text-gray-300 hover:text-white">
                        {{ $photo->gallery->name }}
                    </a>
                @endif
            </nav>

            <div class="flex items-center space-x-4">
                @php $darkMode = true; @endphp
                @include('family.partials.share-button', ['media' => $photo, 'mediaType' => 'photo'])
                <a href="{{ route('family.photos.download', $photo->id) }}" download class="text-gray-300 hover:text-white p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Photo Display -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Photo -->
            <div class="flex-1">
                <div class="relative">
                    <img class="w-full h-auto max-h-[80vh] object-contain rounded-lg wasabi-monitored"
                         src="{{ $photo->signed_url }}"
                         alt="{{ $photo->title ?? 'Photo' }}">
                </div>
            </div>

            <!-- Photo Details -->
            <div class="lg:w-80 bg-gray-900 rounded-lg p-6">
                @if($photo->title)
                    <h1 class="text-2xl font-bold text-white mb-4">{{ $photo->title }}</h1>
                @endif

                @if($photo->description)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-300 mb-2">Description</h3>
                        <p class="text-gray-100">{{ $photo->description }}</p>
                    </div>
                @endif

                <!-- Photo Info -->
                <div class="space-y-4">
                    @if($photo->gallery)
                        <div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Gallery</h3>
                            <a href="{{ route('galleries.show', $photo->gallery->slug) }}" class="text-indigo-400 hover:text-indigo-300">
                                {{ $photo->gallery->name }}
                            </a>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-300 mb-1">Date Taken</h3>
                        <p class="text-gray-100">{{ $photo->taken_at ? $photo->taken_at->format('F j, Y g:i A') : $photo->created_at->format('F j, Y') }}</p>
                    </div>

                    @if($photo->file_size)
                        <div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">File Size</h3>
                            <p class="text-gray-100">{{ number_format($photo->file_size / 1024 / 1024, 2) }} MB</p>
                        </div>
                    @endif

                    @if($photo->dimensions)
                        <div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Dimensions</h3>
                            <p class="text-gray-100">{{ $photo->dimensions }}</p>
                        </div>
                    @endif

                    @if($photo->camera_make || $photo->camera_model)
                        <div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Camera</h3>
                            <p class="text-gray-100">{{ trim($photo->camera_make . ' ' . $photo->camera_model) }}</p>
                        </div>
                    @endif

                    @if($photo->tags && $photo->tags->count() > 0)
                        <div>
                            <h3 class="text-sm font-medium text-gray-300 mb-2">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($photo->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-600 text-white">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-8 space-y-3">
                    <a href="{{ $photo->url }}" download class="w-full flex justify-center items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-100 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Original
                    </a>

                    <button type="button" class="w-full flex justify-center items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-100 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                        </svg>
                        Share Photo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('family.partials.sharing-modal', ['darkMode' => true])

@push('scripts')
<script>
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.location.href = '{{ route('photos.index') }}';
        }
    });
</script>
@endpush
@endsection
