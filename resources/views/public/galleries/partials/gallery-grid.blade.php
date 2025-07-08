<!-- Gallery Grid Partial -->
<div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
    @foreach($galleries as $gallery)
    <a href="{{ route('galleries.show', $gallery->slug) }}" class="group">
        <div class="aspect-w-1 aspect-h-1 xl:aspect-w-7 xl:aspect-h-8 w-full overflow-hidden rounded-lg bg-gray-200 shadow-md">
            <img src="{{ asset('/storage/' . ($gallery->cover_image)) }}" alt="{{ $gallery->name }}" class="h-full w-full object-cover object-center group-hover:opacity-75">
        </div>
        <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $gallery->name }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ $gallery->photos->count() }} photos</p>
    </a>
    @endforeach
</div>
