/**
 * Wasabi Image Auto-Refresh
 *
 * This script automatically refreshes Wasabi S3 images when their URLs expire.
 * It works by:
 * 1. Attaching error handlers to all images loaded from Wasabi
 * 2. When an image fails to load, it fetches a new signed URL
 * 3. Updates the image src with the new URL
 * 4. Periodically refreshes URLs before they expire
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize by adding handlers to existing images
    initWasabiImageHandlers();

    // Set up a periodic check for all Wasabi images
    setInterval(refreshAllWasabiImages, 240000); // Refresh every 4 minutes

    // Set up a mutation observer to watch for new images added to the DOM
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                initWasabiImageHandlers();
            }
        });
    });

    // Start observing the document body for DOM changes
    observer.observe(document.body, { childList: true, subtree: true });
});

/**
 * Initialize error handlers on all Wasabi images
 */
function initWasabiImageHandlers() {
    // Find all images that use signed URLs from Wasabi storage
    const wasabiImages = document.querySelectorAll('img[src*="storage/signed"]');

    wasabiImages.forEach(img => {
        if (!img.hasAttribute('data-wasabi-monitored')) {
            // Add error handler to refresh URL when image fails to load
            img.addEventListener('error', function() {
                refreshWasabiImage(this);
            });

            // Mark the image as monitored
            img.setAttribute('data-wasabi-monitored', 'true');

            // Extract and store the path and type for future refreshes
            const urlObj = new URL(img.src);
            const path = urlObj.searchParams.get('path');
            const type = urlObj.searchParams.get('type');

            if (path) {
                img.setAttribute('data-wasabi-path', path);
                img.setAttribute('data-wasabi-type', type || 'short');
                img.setAttribute('data-wasabi-last-refresh', Date.now().toString());
            }
        }
    });
}

/**
 * Refresh a specific Wasabi image URL
 */
function refreshWasabiImage(imgElement) {
    // Try to get path and type from data attributes first
    let path = imgElement.getAttribute('data-wasabi-path');
    let type = imgElement.getAttribute('data-wasabi-type');

    // If not available in data attributes, try to extract from current URL
    if (!path) {
        try {
            const urlObj = new URL(imgElement.src);
            path = urlObj.searchParams.get('path');
            type = urlObj.searchParams.get('type') || 'short';

            // Store for future use
            if (path) {
                imgElement.setAttribute('data-wasabi-path', path);
                imgElement.setAttribute('data-wasabi-type', type);
            }
        } catch (e) {
            console.error('Error parsing Wasabi image URL:', e);
            return;
        }
    }

    if (!path) return;

    // Make AJAX request to get new signed URL
    fetch(`/admin/storage/signed-url?path=${encodeURIComponent(path)}&type=${type}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.url) {
            // Update image with new URL
            imgElement.src = data.url;
            imgElement.setAttribute('data-wasabi-last-refresh', Date.now().toString());
        }
    })
    .catch(error => console.error('Error refreshing Wasabi image URL:', error));
}

/**
 * Refresh all Wasabi images on the page
 */
function refreshAllWasabiImages() {
    const wasabiImages = document.querySelectorAll('img[data-wasabi-monitored="true"]');
    wasabiImages.forEach(img => refreshWasabiImage(img));
}

// Make the refresh function globally available
window.refreshAllWasabiImages = refreshAllWasabiImages;
