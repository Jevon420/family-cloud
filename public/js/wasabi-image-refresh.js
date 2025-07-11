/**
 * Wasabi Image Auto-Refresh
 *
 * This script automatically refreshes Wasabi S3 images when their URLs expire.
 * It works by:
 * 1. Attaching error handlers to all images loaded from Wasabi
 * 2. When an image fails to load, it fetches a new signed URL
 * 3. Updates the image src with the new URL
 */
document.addEventListener('DOMContentLoaded', function() {
    // Identify all Wasabi images by looking for signedUrl in the src
    const wasabiImages = document.querySelectorAll('img[src*="storage/signedUrl"]');

    wasabiImages.forEach(img => {
        // Add error handler to refresh URL when image fails to load
        img.addEventListener('error', function() {
            refreshWasabiImage(this);
        });
    });

    // Function to refresh a Wasabi image URL
    function refreshWasabiImage(imgElement) {
        // Get current URL
        const currentSrc = imgElement.src;
        // Extract path and type from current URL
        const urlObj = new URL(currentSrc);
        const path = urlObj.searchParams.get('path');
        const type = urlObj.searchParams.get('type');

        if (!path || !type) return;

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
            }
        })
        .catch(error => console.error('Error refreshing Wasabi image URL:', error));
    }

    // Auto-refresh images periodically for long sessions
    setInterval(function() {
        wasabiImages.forEach(img => refreshWasabiImage(img));
    }, 60000); // Refresh every minute (60,000 ms)
});
