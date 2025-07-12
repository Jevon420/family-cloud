@extends('admin.layouts.app')

@section('title', 'Photo Management')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-full overflow-x-hidden">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Photo Management</h1>
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <button id="toggleView" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg id="gridIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h4v4H4V6zm6 0h4v4h-4V6zm6 0h4v4h-4V6zM4 14h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z" /></svg>
                <svg id="listIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <span id="toggleText">Grid View</span>
            </button>
            <a href="{{ route('admin.photos.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Upload New Photo
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <form method="GET" action="" class="mb-6 flex flex-wrap gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search photos..." class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        <select name="visibility" class="w-full sm:w-40 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Visibilities</option>
            <option value="public" @if(request('visibility')==='public') selected @endif>Public</option>
            <option value="private" @if(request('visibility')==='private') selected @endif>Private</option>
            <option value="shared" @if(request('visibility')==='shared') selected @endif>Shared</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Filter</button>
        @if(request('search') || request('visibility'))
        <a href="{{ route('admin.photos.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold hover:bg-gray-400">Reset</a>
        @endif
    </form>

    @if($photos->count())
    <div id="photoContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 transition-all">
        @foreach($photos as $photo)
        <div class="bg-white rounded-lg shadow-md flex flex-col md:flex-row md:items-center p-4 group transition-all">
            <div class="flex-shrink-0 w-full md:w-32 h-32 flex items-center justify-center overflow-hidden mb-4 md:mb-0 md:mr-4">
                @if($photo->thumbnail_path)
                <img class="object-cover w-full h-full rounded-md wasabi-monitored" src="{{ route('admin.storage.signedUrl', ['path' => $photo->thumbnail_path]) }}" alt="{{ $photo->name }}" onerror="this.src='{{ asset('images/placeholder.php') }}'; console.log('Image failed to load: {{ $photo->thumbnail_path }}');">

                @elseif($photo->file_path)
                <img class="object-cover w-full h-full rounded-md wasabi-monitored" src="{{ route('admin.storage.signedUrl', ['path' => $photo->file_path]) }}" alt="{{ $photo->name }}" onerror="this.src='{{ asset('images/placeholder.php') }}'; console.log('Image failed to load: {{ $photo->file_path }}');">
                @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-md">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                @endif
            </div>
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.photos.show', $photo) }}" class="text-lg font-semibold text-gray-900 hover:text-indigo-600">{{ $photo->name }}</a>
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ number_format($photo->file_size / 1024, 2) }} KB</span>
                    </div>
                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($photo->description, 60) }}</div>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-500">
                        <span>By {{ $photo->user->name }}</span>
                        <span class="hidden sm:inline">|</span>
                        <span>{{ $photo->user->email }}</span>
                        <span class="hidden sm:inline">|</span>
                        <span>{{ $photo->created_at->format('M d, Y') }}</span>
                        <span class="hidden sm:inline">|</span>
                        @if($photo->visibility === 'public')
                            <span class="px-2 inline-flex rounded-full bg-green-100 text-green-800">Public</span>
                        @elseif($photo->visibility === 'private')
                            <span class="px-2 inline-flex rounded-full bg-red-100 text-red-800">Private</span>
                        @else
                            <span class="px-2 inline-flex rounded-full bg-yellow-100 text-yellow-800">Shared</span>
                        @endif
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-500">
                        @if($photo->gallery)
                        <span>Gallery: <a href="{{ route('admin.galleries.show', $photo->gallery) }}" class="text-indigo-600 hover:text-indigo-900">{{ $photo->gallery->name }}</a></span>
                        @else
                        <span>No Gallery</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.photos.show', $photo) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                    <a href="{{ route('admin.photos.edit', $photo) }}" class="text-yellow-600 hover:text-yellow-900 font-medium">Edit</a>
                    <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Are you sure you want to delete this photo?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $photos->links() }}</div>
    @else
    <div class="text-center text-gray-500 py-12">
        No photos found. <a href="{{ route('admin.photos.create') }}" class="text-indigo-600 hover:text-indigo-900">Upload one now</a>.
    </div>
    @endif
</div>

@push('scripts')
<script>
    const toggleBtn = document.getElementById('toggleView');
    const photoContainer = document.getElementById('photoContainer');
    const gridIcon = document.getElementById('gridIcon');
    const listIcon = document.getElementById('listIcon');
    const toggleText = document.getElementById('toggleText');
    let isGrid = true;
    toggleBtn.addEventListener('click', function() {
        isGrid = !isGrid;
        if (isGrid) {
            photoContainer.classList.remove('flex', 'flex-col');
            photoContainer.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
            gridIcon.classList.remove('hidden');
            listIcon.classList.add('hidden');
            toggleText.textContent = 'Grid View';
        } else {
            photoContainer.classList.remove('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
            photoContainer.classList.add('flex', 'flex-col');
            gridIcon.classList.add('hidden');
            listIcon.classList.remove('hidden');
            toggleText.textContent = 'List View';
        }
    });
</script>
@endpush
@endsection
