@if($media->visibility === 'private' || $media->isPrivate())
<button onclick="openSharingModal('{{ $mediaType }}', {{ $media->id }}, '{{ addslashes($media->name) }}')"
        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md {{ $darkMode ? 'text-gray-300 bg-gray-700 hover:bg-gray-600' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        title="Share {{ $media->name }}">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
    </svg>
    Share
</button>
@endif
