<!-- Photo Grid Partial -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($photos as $photo)
    <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
        <!-- Photo Image -->
        <div class="aspect-square relative overflow-hidden">
            <x-lazy-image
                :src="asset('/storage/' . ($photo->thumbnail_path ?? $photo->file_path))"
                :alt="$photo->name"
                class="group-hover:scale-110 transition-transform duration-700"
                aspect-ratio="aspect-square"
            />

            <!-- Overlay with actions -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <h3 class="font-medium text-sm truncate">{{ $photo->name }}</h3>
                        </div>
                        <div class="flex space-x-2">
                            <button class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Link Overlay -->
        <a href="{{ route('photos.show', $photo->id) }}" class="absolute inset-0"></a>
    </div>
    @endforeach
</div>
