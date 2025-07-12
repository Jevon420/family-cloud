<!-- Photo Detail Partial -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="relative">
        <img src="{{ $photo->signed_url }}" alt="{{ $photo->name }}" class="w-full object-cover">

        <!-- Navigation Arrows -->
        <div class="absolute inset-0 flex items-center justify-between p-4">
            @if($prevPhoto)
            <a href="{{ route('photos.show', $prevPhoto->id) }}" class="prev-photo bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2" data-photo-id="{{ $prevPhoto->id }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            @else
            <div></div> <!-- Empty div for spacing -->
            @endif

            @if($nextPhoto)
            <a href="{{ route('photos.show', $nextPhoto->id) }}" class="next-photo bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2" data-photo-id="{{ $nextPhoto->id }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @else
            <div></div> <!-- Empty div for spacing -->
            @endif
        </div>
    </div>

    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $photo->name }}</h1>

        @if($photo->description)
        <div class="text-gray-700 mb-4">
            {{ $photo->description }}
        </div>
        @endif

        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
            <span>Uploaded: {{ $photo->created_at->format('F j, Y') }}</span>
            @if($photo->gallery)
            <a href="{{ route('galleries.show', $photo->gallery->slug) }}" class="text-indigo-600 hover:text-indigo-500">
                Back to "{{ $photo->gallery->name }}"
            </a>
            @endif
        </div>

        @if($photo->tags && $photo->tags->count() > 0)
        <div class="mt-2">
            <h3 class="text-sm font-medium text-gray-900">Tags:</h3>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($photo->tags as $tag)
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $tag->name }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
