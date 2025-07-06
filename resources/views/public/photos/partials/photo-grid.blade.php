<!-- Photo Grid Partial -->
<div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
    @foreach($photos as $photo)
    <a href="{{ route('photos.show', $photo->id) }}" class="group photo-link" data-photo-id="{{ $photo->id }}">
        <div class="aspect-w-1 aspect-h-1 xl:aspect-w-7 xl:aspect-h-8 w-full overflow-hidden rounded-lg bg-gray-200 shadow-md">
            <img src="{{ asset($photo->thumbnail_path ?? $photo->file_path) }}" alt="{{ $photo->name }}" class="h-full w-full object-cover object-center group-hover:opacity-75">
        </div>
        <h3 class="mt-4 text-sm text-gray-700">{{ $photo->name }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ $photo->created_at->format('M j, Y') }}</p>
    </a>
    @endforeach
</div>
