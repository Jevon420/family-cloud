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

            <!-- Visibility status -->
            <div class="mb-4">
                <div class="flex items-center">
                    <span id="visibilityStatus" class="px-2 py-1 rounded-full text-xs font-semibold mr-2"></span>
                    <button id="changeVisibilityBtn" class="text-sm {{ $darkMode ? 'text-indigo-300 hover:text-indigo-200' : 'text-indigo-600 hover:text-indigo-800' }}">
                        Change Visibility
                    </button>
                </div>
                <div id="visibilityOptions" class="mt-2 hidden">
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="visibility" value="private" class="mr-2">
                            <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Private (only you and people you share with)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="visibility" value="public" class="mr-2">
                            <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Public (anyone with the link)</span>
                        </label>
                    </div>
                    <button onclick="updateVisibility()" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Update
                    </button>
                </div>
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

                <!-- Multiple user selection -->
                <div id="selectedUsers" class="mb-3 flex flex-wrap gap-2 hidden"></div>

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
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions" value="edit" class="mr-2">
                            <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Edit</span>
                        </label>
                    </div>
                </div>

                <!-- Notification options -->
                <div class="mb-3">
                    <label class="flex items-center">
                        <input type="checkbox" id="sendNotification" checked class="mr-2">
                        <span class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Send email notification</span>
                    </label>
                </div>

                <!-- Expiration -->
                <div class="mb-3">
                    <label class="block text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-2">Expires at (optional)</label>
                    <input type="datetime-local"
                           id="expiresAt"
                           class="w-full px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900' }} border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <button id="shareWithUsersBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Share with Selected Users
                </button>

                <!-- Currently shared with -->
                <div id="currentShares" class="mt-4">
                    <h5 class="text-sm font-medium {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }} mb-2">Currently shared with:</h5>
                    <div id="sharedUsersList" class="space-y-2"></div>
                </div>
            </div>

            <!-- Share link -->
            <div class="mb-6 border-t {{ $darkMode ? 'border-gray-600' : 'border-gray-200' }} pt-4">
                <h4 class="text-md font-medium {{ $darkMode ? 'text-white' : 'text-gray-900' }} mb-3">Share via link</h4>

                <div id="publicLinkInfo" class="mb-3 hidden">
                    <div class="p-2 rounded {{ $darkMode ? 'bg-gray-700' : 'bg-gray-100' }}">
                        <p class="text-sm {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">
                            This item is public. Anyone with the link below can access it.
                        </p>
                    </div>
                </div>

                <div id="privateLinkInfo" class="mb-3 hidden">
                    <div class="p-2 rounded {{ $darkMode ? 'bg-yellow-900 bg-opacity-30' : 'bg-yellow-100' }}">
                        <p class="text-sm {{ $darkMode ? 'text-yellow-300' : 'text-yellow-700' }}">
                            This item is private. Creating a link will allow temporary access.
                        </p>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button onclick="generateShareLink()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Generate Link
                    </button>
                    <input type="datetime-local"
                           id="linkExpiresAt"
                           placeholder="Link expires at (optional)"
                           class="flex-1 px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900' }} border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="sendLinkNotification" class="mr-2">
                            <span class="text-xs {{ $darkMode ? 'text-gray-300' : 'text-gray-700' }}">Email link</span>
                        </label>
                    </div>
                </div>

                <div id="emailLinkSection" class="mt-2 hidden">
                    <input type="email"
                           id="emailToShare"
                           placeholder="Enter email address"
                           class="w-full px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900' }} border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div id="shareLink" class="mt-3 hidden">
                    <div class="flex items-center space-x-2">
                        <input type="text" id="shareLinkInput" readonly class="flex-1 px-3 py-2 {{ $darkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-900' }} border rounded-md">
                        <button onclick="copyShareLink()" class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Copy
                        </button>
                        <button onclick="emailShareLink()" id="emailLinkBtn" class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 hidden">
                            Email
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2">
                <button onclick="closeSharingModal()" class="px-4 py-2 {{ $darkMode ? 'bg-gray-600 text-white hover:bg-gray-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }} rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sharing Modal Scripts -->
<script>
let currentMediaType = '';
let currentMediaId = '';
let currentVisibility = '';
let selectedUsers = [];

function openSharingModal(mediaType, mediaId, mediaName) {
    currentMediaType = mediaType;
    currentMediaId = mediaId;
    document.getElementById('mediaName').textContent = mediaName;
    document.getElementById('sharingModal').classList.remove('hidden');

    // Clear previous selections
    selectedUsers = [];
    document.getElementById('selectedUsers').innerHTML = '';
    document.getElementById('selectedUsers').classList.add('hidden');

    // Get media visibility and current shares
    getMediaVisibility();
    loadCurrentShares();
}

function getMediaVisibility() {
    fetch(`/family/sharing/media-visibility?media_type=${currentMediaType}&media_id=${currentMediaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentVisibility = data.visibility.visibility || 'private';
                updateVisibilityUI(currentVisibility);

                // Set radio button for current visibility
                document.querySelector(`input[name="visibility"][value="${currentVisibility}"]`).checked = true;
            }
        });
}

function updateVisibilityUI(visibility) {
    const statusElement = document.getElementById('visibilityStatus');

    if (visibility === 'public') {
        statusElement.textContent = 'Public';
        statusElement.className = 'px-2 py-1 rounded-full text-xs font-semibold mr-2 bg-green-100 text-green-800';
        document.getElementById('publicLinkInfo').classList.remove('hidden');
        document.getElementById('privateLinkInfo').classList.add('hidden');
    } else {
        statusElement.textContent = 'Private';
        statusElement.className = 'px-2 py-1 rounded-full text-xs font-semibold mr-2 bg-red-100 text-red-800';
        document.getElementById('publicLinkInfo').classList.add('hidden');
        document.getElementById('privateLinkInfo').classList.remove('hidden');
    }
}

function updateVisibility() {
    const visibility = document.querySelector('input[name="visibility"]:checked').value;
    const notify = true; // Always notify the owner of visibility changes

    fetch('/family/sharing/update-visibility', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            media_type: currentMediaType,
            media_id: currentMediaId,
            visibility: visibility,
            notify_owner: notify
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentVisibility = visibility;
            updateVisibilityUI(visibility);
            document.getElementById('visibilityOptions').classList.add('hidden');

            // If visibility changed and token was regenerated, hide the share link section
            if (data.token_regenerated) {
                document.getElementById('shareLink').classList.add('hidden');
                alert('Visibility changed to private. Any existing share links have been invalidated for security.');
            } else if (data.share_link) {
                // If visibility changed to public and we have a share link, show it
                document.getElementById('shareLinkInput').value = data.share_link;
                document.getElementById('shareLink').classList.remove('hidden');
                if (visibility === 'public') {
                    alert('Visibility changed to public. Anyone with the link can now access this item.');
                }
            }
        } else {
            alert('Error updating visibility');
        }
    });
}

document.getElementById('changeVisibilityBtn').addEventListener('click', function() {
    const visibilityOptions = document.getElementById('visibilityOptions');
    visibilityOptions.classList.toggle('hidden');
});

document.getElementById('sendLinkNotification').addEventListener('change', function() {
    const emailLinkSection = document.getElementById('emailLinkSection');
    if (this.checked) {
        emailLinkSection.classList.remove('hidden');
    } else {
        emailLinkSection.classList.add('hidden');
    }
});

function closeSharingModal() {
    document.getElementById('sharingModal').classList.add('hidden');
    document.getElementById('shareLink').classList.add('hidden');
    document.getElementById('userSearchResults').classList.add('hidden');
    document.getElementById('userSearch').value = '';
    document.getElementById('visibilityOptions').classList.add('hidden');
    document.getElementById('emailLinkSection').classList.add('hidden');
    document.getElementById('sendLinkNotification').checked = false;

    currentMediaType = '';
    currentMediaId = '';
    currentVisibility = '';
    selectedUsers = [];
}

function loadCurrentShares() {
    fetch(`/family/sharing/shared-users?media_type=${currentMediaType}&media_id=${currentMediaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const sharedUsersList = document.getElementById('sharedUsersList');
                sharedUsersList.innerHTML = '';

                if (data.shared_users.length === 0) {
                    sharedUsersList.innerHTML = '<p class="text-sm text-gray-500">No users currently have access</p>';
                    return;
                }

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

                    if (data.users.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-2 text-sm text-gray-500">No users found</div>';
                        resultsDiv.classList.remove('hidden');
                        return;
                    }

                    data.users.forEach(user => {
                        const userDiv = document.createElement('div');
                        userDiv.className = 'p-2 hover:{{ $darkMode ? "bg-gray-600" : "bg-gray-100" }} cursor-pointer';
                        userDiv.innerHTML = `
                            <div class="font-medium">${user.name}</div>
                            <div class="text-sm {{ $darkMode ? "text-gray-400" : "text-gray-500" }}">${user.email}</div>
                        `;
                        userDiv.onclick = () => selectUser(user);
                        resultsDiv.appendChild(userDiv);
                    });

                    resultsDiv.classList.remove('hidden');
                }
            });
    } else {
        document.getElementById('userSearchResults').classList.add('hidden');
    }
});

function selectUser(user) {
    // Check if user is already selected
    if (selectedUsers.some(u => u.id === user.id)) {
        return;
    }

    selectedUsers.push(user);
    document.getElementById('userSearch').value = '';
    document.getElementById('userSearchResults').classList.add('hidden');

    updateSelectedUsersUI();
}

function updateSelectedUsersUI() {
    const selectedUsersElement = document.getElementById('selectedUsers');
    selectedUsersElement.innerHTML = '';

    if (selectedUsers.length > 0) {
        selectedUsersElement.classList.remove('hidden');

        selectedUsers.forEach(user => {
            const userTag = document.createElement('div');
            userTag.className = 'inline-flex items-center px-2 py-1 rounded {{ $darkMode ? "bg-gray-700" : "bg-gray-200" }}';
            userTag.innerHTML = `
                <span class="mr-1 text-sm">${user.name}</span>
                <button onclick="removeSelectedUser(${user.id})" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            selectedUsersElement.appendChild(userTag);
        });
    } else {
        selectedUsersElement.classList.add('hidden');
    }
}

function removeSelectedUser(userId) {
    selectedUsers = selectedUsers.filter(user => user.id !== userId);
    updateSelectedUsersUI();
}

document.getElementById('shareWithUsersBtn').addEventListener('click', function() {
    if (selectedUsers.length === 0) {
        alert('Please select at least one user to share with');
        return;
    }

    const permissions = Array.from(document.querySelectorAll('input[name="permissions"]:checked')).map(cb => cb.value);
    const expiresAt = document.getElementById('expiresAt').value || null;
    const sendNotification = document.getElementById('sendNotification').checked;

    // Share with each selected user
    const sharePromises = selectedUsers.map(user =>
        fetch('/family/sharing/share-with-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                media_type: currentMediaType,
                media_id: currentMediaId,
                user_id: user.id,
                permissions: permissions,
                expires_at: expiresAt,
                send_notification: sendNotification
            })
        })
        .then(response => response.json())
    );

    Promise.all(sharePromises)
        .then(results => {
            const allSuccessful = results.every(data => data.success);

            if (allSuccessful) {
                alert(`Successfully shared with ${selectedUsers.length} user(s)`);
                selectedUsers = [];
                updateSelectedUsersUI();
                document.getElementById('userSearch').value = '';
                loadCurrentShares();
            } else {
                alert('Error sharing with some users');
            }
        });
});

function generateShareLink() {
    const expiresAt = document.getElementById('linkExpiresAt').value || null;
    const sendNotification = document.getElementById('sendLinkNotification').checked;

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

            // Show email button if notification checkbox is checked
            if (sendNotification) {
                document.getElementById('emailLinkBtn').classList.remove('hidden');
            } else {
                document.getElementById('emailLinkBtn').classList.add('hidden');
            }
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

function emailShareLink() {
    const email = document.getElementById('emailToShare').value.trim();
    if (!email) {
        alert('Please enter an email address');
        return;
    }

    const shareLink = document.getElementById('shareLinkInput').value;

    fetch('/family/sharing/email-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            media_type: currentMediaType,
            media_id: currentMediaId,
            email: email,
            share_link: shareLink
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Share link sent to ${email}`);
            // Clear the email field
            document.getElementById('emailToShare').value = '';
        } else {
            alert('Error sending share link email');
        }
    });
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
