<!-- Gallery Grid Partial -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($galleries as $gallery)
    <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
        <!-- Gallery Image -->
        <div class="aspect-w-16 aspect-h-10 relative overflow-hidden">
            <x-lazy-image
                :src="$gallery->cover_image ? $gallery->signed_cover_url : asset('/images/placeholder-gallery.jpg')"
                :alt="$gallery->name"
                class="group-hover:scale-110 transition-transform duration-700"
                aspect-ratio="aspect-w-16 aspect-h-10"
            />

            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <span class="text-sm font-medium">{{ $gallery->photos->count() }} photos</span>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Info -->
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">{{ $gallery->name }}</h3>
            @if($gallery->description)
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($gallery->description, 100) }}</p>
            @endif
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>{{ $gallery->created_at->format('M j, Y') }}</span>
                <span class="flex items-center">
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $gallery->photos->count() }}
                </span>
            </div>
        </div>

        <!-- Link Overlay -->
        <a href="{{ route('galleries.show', $gallery->slug) }}" class="absolute inset-0"></a>
    </div>
    @endforeach
</div>
