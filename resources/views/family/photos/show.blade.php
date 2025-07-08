@extends('family.layouts.app')

@section('title', $photo->title)

@section('content')
<div class="container px-2 sm:px-4 py-2">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div class="flex items-center w-full sm:w-auto mb-4 sm:mb-0">
            <a href="{{ route('family.photos.index') }}" class="mr-2 text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-semibold {{ $darkMode ? 'text-white' : 'text-gray-800' }}">{{ $photo->title }}</h1>
        </div>

        <!-- Options Icon for Mobile View -->
        <div x-data="{ showOptions: false }" class="relative w-full sm:w-auto">
            <button type="button" x-on:click="showOptions = !showOptions" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50' }} w-full sm:hidden">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd" />
                </svg>
                Options
            </button>

            <div x-show="showOptions" x-cloak class="absolute top-full left-0 w-full bg-white shadow-md rounded-md mt-2 z-10">
                @include('family.partials.share-button', ['media' => $photo, 'mediaType' => 'photo', 'darkMode' => $darkMode])
                <a href="{{ route('family.photos.edit', $photo->id) }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Photo</a>
                <a href="{{ route('family.photos.download', $photo->id) }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a>
                <button type="button" onclick="openModal({{ $photo->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Gallery</button>
            </div>

            <!-- Action Buttons for Desktop View -->
            <div class="hidden sm:flex flex-wrap space-x-2">
                @include('family.partials.share-button', ['media' => $photo, 'mediaType' => 'photo', 'darkMode' => $darkMode])
                <a href="{{ route('family.photos.edit', $photo->id) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Photo
                </a>
                <a href="{{ route('family.photos.download', $photo->id) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Download
                </a>
                <button type="button" onclick="openModal({{ $photo->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View Gallery
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden {{ $darkMode ? 'bg-gray-800' : '' }}">
        <div class="p-4 sm:p-6">
            <div class="flex flex-wrap justify-between mb-4">
                <div class="w-full sm:w-auto">
                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                        From gallery: <a href="{{ route('family.galleries.show', $photo->gallery->slug) }}"
                                      class="text-indigo-600 hover:text-indigo-800 {{ $darkMode ? 'text-indigo-400 hover:text-indigo-300' : '' }}">
                                      {{ $photo->gallery->title }}</a>
                    </p>
                    <p class="text-sm {{ $darkMode ? 'text-gray-400' : 'text-gray-600' }}">
                        Added: {{ $photo->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex flex-wrap mt-2 sm:mt-0 space-x-2 w-full sm:w-auto">
                    @if($prevPhoto)
                        <a href="{{ route('family.photos.show', $prevPhoto->id) }}"
                           class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'text-white bg-gray-700 hover:bg-gray-600 border-gray-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Previous
                        </a>
                    @endif

                    @if($nextPhoto)
                        <a href="{{ route('family.photos.show', $nextPhoto->id) }}"
                           class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $darkMode ? 'text-white bg-gray-700 hover:bg-gray-600 border-gray-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="my-4 bg-gray-100 p-1 rounded-lg {{ $darkMode ? 'bg-gray-700' : '' }}">
                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}"
                     class="w-full h-auto max-h-[80vh] object-contain mx-auto cursor-pointer photo-trigger"
                     data-photo-id="{{ $photo->id }}"
                     data-photo-url="{{ asset('storage/' . $photo->file_path) }}"
                     data-photo-title="{{ $photo->title }}"
                     data-photo-date="{{ $photo->created_at->format('F j, Y') }}">
            </div>

            @if($photo->description)
                <div class="mt-4">
                    <h3 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">Description</h3>
                    <p class="mt-2 {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">{{ $photo->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Photo Carousel Modal -->
    <div id="photoCarouselModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-700 bg-opacity-75 backdrop-blur-sm">
        <div class="relative w-full h-full flex flex-col">
            <!-- Close Button -->
            <button id="closeCarouselModal" class="absolute top-4 right-4 z-10 bg-red-500 text-white rounded-full p-3 hover:bg-red-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Main Photo Display -->
            <div class="flex-1 flex items-center justify-center p-4">
                <div class="relative max-w-4xl max-h-full">
                    <!-- Navigation Buttons -->
                    <button id="prevPhotoBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-3 hover:bg-opacity-70 transition-all z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button id="nextPhotoBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-3 hover:bg-opacity-70 transition-all z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Photo Container -->
                    <div class="relative">
                        <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white p-3 rounded-lg">
                            <h3 id="modalPhotoTitle" class="text-lg font-bold"></h3>
                            <p id="modalPhotoDate" class="text-sm opacity-75"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Strip -->
            <div class="bg-black bg-opacity-50 backdrop-blur-sm p-4">
                <div class="flex space-x-2 overflow-x-auto scrollbar-hide" id="photoThumbnails">
                    <!-- Thumbnails will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get all gallery photos for this photo's gallery
        const photoData = <?php echo json_encode($photo->gallery->photos->map(function($galleryPhoto) {
            return [
                'id' => $galleryPhoto->id,
                'url' => asset('storage/' . $galleryPhoto->file_path),
                'title' => $galleryPhoto->name,
                'date' => $galleryPhoto->created_at->format('F j, Y')
            ];
        })->values()->all()); ?>;

        let currentPhotoIndex = 0;
        const modal = document.getElementById('photoCarouselModal');
        const modalPhoto = document.getElementById('modalPhoto');
        const modalPhotoTitle = document.getElementById('modalPhotoTitle');
        const modalPhotoDate = document.getElementById('modalPhotoDate');
        const thumbnailContainer = document.getElementById('photoThumbnails');

        // Generate thumbnails
        function generateThumbnails() {
            thumbnailContainer.innerHTML = '';
            photoData.forEach((photo, index) => {
                const thumbnail = document.createElement('div');
                thumbnail.className = `flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden cursor-pointer transition-all duration-300 ${index === currentPhotoIndex ? 'ring-4 ring-blue-500 scale-110' : 'opacity-70 hover:opacity-100'}`;
                thumbnail.innerHTML = `<img src="${photo.url}" alt="${photo.title}" class="w-full h-full object-cover">`;
                thumbnail.onclick = () => showPhoto(index);
                thumbnailContainer.appendChild(thumbnail);
            });

            // Center thumbnails
            thumbnailContainer.style.justifyContent = 'center';
        }

        // Show photo at index
        function showPhoto(index) {
            if (index < 0 || index >= photoData.length) return;

            currentPhotoIndex = index;
            const photo = photoData[index];

            modalPhoto.src = photo.url;
            modalPhoto.alt = photo.title;
            modalPhotoTitle.textContent = photo.title;
            modalPhotoDate.textContent = photo.date;

            // Update thumbnails
            generateThumbnails();

            // Scroll to current thumbnail
            const activeThumbnail = thumbnailContainer.children[index];
            if (activeThumbnail) {
                activeThumbnail.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Open modal
        function openModal(photoId) {
            const photoIndex = photoData.findIndex(photo => photo.id == photoId);
            if (photoIndex !== -1) {
                showPhoto(photoIndex);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Event listeners
        document.getElementById('closeCarouselModal').onclick = closeModal;
        document.getElementById('prevPhotoBtn').onclick = () => showPhoto(currentPhotoIndex - 1);
        document.getElementById('nextPhotoBtn').onclick = () => showPhoto(currentPhotoIndex + 1);

        // Photo trigger clicks
        document.querySelectorAll('.photo-trigger').forEach(trigger => {
            trigger.onclick = (e) => {
                e.preventDefault();
                const photoId = trigger.dataset.photoId;
                openModal(photoId);
            };
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeModal();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        showPhoto(currentPhotoIndex - 1);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        showPhoto(currentPhotoIndex + 1);
                        break;
                }
            }
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        modalPhoto.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        modalPhoto.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                // Swipe left - next photo
                showPhoto(currentPhotoIndex + 1);
            }
            if (touchEndX > touchStartX + 50) {
                // Swipe right - previous photo
                showPhoto(currentPhotoIndex - 1);
            }
        }
    </script>

    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</div>

@include('family.partials.sharing-modal', ['darkMode' => $darkMode])
@endsection
