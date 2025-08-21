@props([
    'src',
    'alt' => '',
    'class' => '',
    'thumbnailSrc' => null,
    'blurredSrc' => null,
    'aspectRatio' => 'aspect-square',
    'loading' => 'lazy',
    'showSpinner' => true,
    'showPlaceholder' => true
])

<div class="lazy-image-container relative overflow-hidden {{ $aspectRatio }} {{ $class }}"
     x-data="{ loaded: false, loading: false, error: false }"
     x-init="
        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !loaded && !loading) {
                    loading = true;
                    const img = new Image();
                    img.onload = () => { loaded = true; loading = false; };
                    img.onerror = () => { error = true; loading = false; };
                    img.src = '{{ $src }}';
                }
            });
        }, { rootMargin: '50px' });
        observer.observe($el);
     ">

    <!-- Blurred placeholder background -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-300 animate-pulse"
         x-show="!loaded && !error"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        @if($blurredSrc)
            <img src="{{ $blurredSrc }}"
                 alt=""
                 class="w-full h-full object-cover filter blur-sm scale-110 opacity-60">
        @endif
    </div>

    <!-- Loading spinner -->
    @if($showSpinner)
    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-10"
         x-show="loading"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="loading-spinner">
            <svg class="animate-spin h-8 w-8 text-white drop-shadow-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
    @endif

    <!-- Main image -->
    <img src="{{ $thumbnailSrc ?? $src }}"
         alt="{{ $alt }}"
         class="w-full h-full object-cover transition-opacity duration-500"
         x-show="loaded"
         x-transition:enter="transition-opacity duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         loading="{{ $loading }}">

    <!-- Error state -->
    <div class="absolute inset-0 flex items-center justify-center bg-gray-100"
         x-show="error"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="text-center text-gray-400">
            <svg class="mx-auto h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm">Failed to load</p>
        </div>
    </div>
</div>
