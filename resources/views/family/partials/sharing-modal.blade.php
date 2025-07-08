<!-- Sharing Modal -->
<div id="sharingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md {{ $darkMode ? 'bg-gray-800' : 'bg-white' }}">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }}">
                    Share <span id="mediaName"></span>
                </h3>
                <button onclick="closeSharingModal()" class="{{ $darkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-500 hover:text-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Share with specific users -->
            <div class="mb-6">
                <h4 class="text-md font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-3">Share with specific users</h4>

                <!-- User search -->
                <div class="relative mb-3">
                    <input type="text"
                           id="userSearch"
                           placeholder="Search users by name or email..."
                           class="w-full px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900' }} border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div id="userSearchResults" class="absolute z-10 w-full mt-1 {{ $darkMode ? 'bg-gray-700 border-gray-600' : 'bg-white border-gray-300' }} border rounded-md shadow-lg hidden"></div>
                </div>

                <!-- Permissions -->
                <div class="mb-3">
                    <label class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-2">Permissions</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions" value="view" checked class="mr-2">
                            <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">View</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions" value="download" class="mr-2">
                            <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Download</span>
                        </label>
                    </div>
                </div>

                <!-- Expiration -->
                <div class="mb-3">
                    <label class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-2">Expires at (optional)</label>
                    <input type="datetime-local"
                           id="expiresAt"
                           class="w-full px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900' }} border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Currently shared with -->
                <div id="currentShares" class="mb-3">
                    <h5 class="text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-2">Currently shared with:</h5>
                    <div id="sharedUsersList" class="space-y-2"></div>
                </div>
            </div>

            <!-- Share link -->
            <div class="mb-6 border-t {{ $darkMode ? 'border-gray-600' : 'border-gray-200' }} pt-4">
                <h4 class="text-md font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-3">Share via link</h4>

                <div class="flex space-x-2">
                    <button onclick="generateShareLink()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Generate Link
                    </button>
                    <input type="datetime-local"
                           id="linkExpiresAt"
                           placeholder="Link expires at (optional)"
                           class="flex-1 px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900' }} border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div id="shareLink" class="mt-3 hidden">
                    <div class="flex items-center space-x-2">
                        <input type="text" id="shareLinkInput" readonly class="flex-1 px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-900' }} border rounded-md">
                        <button onclick="copyShareLink()" class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Copy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2">
                <button onclick="closeSharingModal()" class="px-4 py-2 {{ $darkMode ? 'bg-gray-600 text-white hover:bg-gray-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }} rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sharing Modal Scripts -->
<script>
let currentMediaType = '';
let currentMediaId = '';

function openSharingModal(mediaType, mediaId, mediaName) {
    currentMediaType = mediaType;
    currentMediaId = mediaId;
    document.getElementById('mediaName').textContent = mediaName;
    document.getElementById('sharingModal').classList.remove('hidden');
    loadCurrentShares();
}

function closeSharingModal() {
    document.getElementById('sharingModal').classList.add('hidden');
    document.getElementById('shareLink').classList.add('hidden');
    document.getElementById('userSearchResults').classList.add('hidden');
    document.getElementById('userSearch').value = '';
    currentMediaType = '';
    currentMediaId = '';
}

function loadCurrentShares() {
    fetch(`/family/sharing/shared-users?media_type=${currentMediaType}&media_id=${currentMediaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const sharedUsersList = document.getElementById('sharedUsersList');
                sharedUsersList.innerHTML = '';

                data.shared_users.forEach(share => {
                    const userDiv = document.createElement('div');
                    userDiv.className = 'flex items-center justify-between p-2 {{ $darkMode ? "bg-gray-700" : "bg-gray-100" }} rounded';
                    userDiv.innerHTML = `
                        <div>
                            <span class="font-medium">${share.shared_with.name}</span>
                            <span class="text-sm {{ $darkMode ? "text-gray-400" : "text-gray-500" }}">(${share.shared_with.email})</span>
                            <div class="text-xs {{ $darkMode ? "text-gray-400" : "text-gray-500" }}">
                                Permissions: ${share.permissions.join(', ')}
                                ${share.expires_at ? `• Expires: ${new Date(share.expires_at).toLocaleDateString()}` : ''}
                            </div>
                        </div>
                        <button onclick="removeShare(${share.id})" class="text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    `;
                    sharedUsersList.appendChild(userDiv);
                });
            }
        });
}

// User search functionality
document.getElementById('userSearch').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    if (query.length >= 2) {
        fetch(`/family/sharing/search-users?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const resultsDiv = document.getElementById('userSearchResults');
                    resultsDiv.innerHTML = '';

                    data.users.forEach(user => {
                        const userDiv = document.createElement('div');
                        userDiv.className = 'p-2 hover:{{ $darkMode ? "bg-gray-600" : "bg-gray-100" }} cursor-pointer';
                        userDiv.innerHTML = `
                            <div class="font-medium">${user.name}</div>
                            <div class="text-sm {{ $darkMode ? "text-gray-400" : "text-gray-500" }}">${user.email}</div>
                        `;
                        userDiv.onclick = () => shareWithUser(user.id, user.name);
                        resultsDiv.appendChild(userDiv);
                    });

                    resultsDiv.classList.remove('hidden');
                }
            });
    } else {
        document.getElementById('userSearchResults').classList.add('hidden');
    }
});

function shareWithUser(userId, userName) {
    const permissions = Array.from(document.querySelectorAll('input[name="permissions"]:checked')).map(cb => cb.value);
    const expiresAt = document.getElementById('expiresAt').value || null;

    fetch('/family/sharing/share-with-user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            media_type: currentMediaType,
            media_id: currentMediaId,
            user_id: userId,
            permissions: permissions,
            expires_at: expiresAt
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Successfully shared with ${userName}`);
            document.getElementById('userSearch').value = '';
            document.getElementById('userSearchResults').classList.add('hidden');
            loadCurrentShares();
        } else {
            alert('Error sharing media');
        }
    });
}

function generateShareLink() {
    const expiresAt = document.getElementById('linkExpiresAt').value || null;

    fetch('/family/sharing/generate-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            media_type: currentMediaType,
            media_id: currentMediaId,
            expires_at: expiresAt
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('shareLinkInput').value = data.share_link;
            document.getElementById('shareLink').classList.remove('hidden');
        } else {
            alert('Error generating share link');
        }
    });
}

function copyShareLink() {
    const linkInput = document.getElementById('shareLinkInput');
    linkInput.select();
    document.execCommand('copy');
    alert('Link copied to clipboard!');
}

function removeShare(shareId) {
    if (confirm('Are you sure you want to remove this share?')) {
        fetch(`/family/sharing/remove-share/${shareId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCurrentShares();
            } else {
                alert('Error removing share');
            }
        });
    }
}

// Close modal when clicking outside
document.getElementById('sharingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSharingModal();
    }
});
</script>
