class CameraUpload {
    constructor() {
        this.modal = document.getElementById('cameraUploadModal');
        this.video = document.getElementById('cameraPreview');
        this.canvas = document.getElementById('captureCanvas');
        this.context = this.canvas.getContext('2d');
        this.gallerySelect = document.getElementById('gallerySelect');
        this.cameraError = document.getElementById('cameraError');
        this.stream = null;
        this.capturedImage = null;

        this.initEventListeners();
        this.loadGalleries();
    }

    initEventListeners() {
        // Modal triggers
        document.getElementById('cameraUploadBtn')?.addEventListener('click', () => this.openModal());
        document.getElementById('cameraUploadBtnMobile')?.addEventListener('click', () => this.openModal());
        document.getElementById('closeCameraModal')?.addEventListener('click', () => this.closeModal());

        // Camera controls
        document.getElementById('startCameraBtn')?.addEventListener('click', () => this.startCamera());
        document.getElementById('captureBtn')?.addEventListener('click', () => this.capturePhoto());
        document.getElementById('retakeBtn')?.addEventListener('click', () => this.retakePhoto());
        document.getElementById('uploadBtn')?.addEventListener('click', () => this.uploadPhoto());

        // Close modal when clicking outside
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });
    }

    async loadGalleries() {
        try {
            const response = await fetch('/camera-upload/galleries', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load galleries');
            }

            const data = await response.json();
            this.populateGalleries(data.galleries);
        } catch (error) {
            console.error('Error loading galleries:', error);
            this.gallerySelect.innerHTML = '<option value="">Error loading galleries</option>';
        }
    }

    populateGalleries(galleries) {
        this.gallerySelect.innerHTML = '<option value="">Select a gallery...</option>';
        galleries.forEach(gallery => {
            const option = document.createElement('option');
            option.value = gallery.id;
            option.textContent = gallery.name;
            this.gallerySelect.appendChild(option);
        });
    }

    openModal() {
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    closeModal() {
        this.modal.classList.add('hidden');
        document.body.style.overflow = '';
        this.stopCamera();
        this.resetUI();
    }

    async startCamera() {
        try {
            // Check if device has camera support
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Camera not supported by this browser');
            }

            // Request camera access with better mobile support
            const constraints = {
                video: {
                    facingMode: 'environment', // Try to use back camera on mobile
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };

            // For iOS devices, add specific constraints
            if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                constraints.video.width = { ideal: 1920, max: 1920 };
                constraints.video.height = { ideal: 1080, max: 1080 };
            }

            this.stream = await navigator.mediaDevices.getUserMedia(constraints);

            this.video.srcObject = this.stream;
            this.video.play();

            // Show video and capture button
            this.video.classList.remove('hidden');
            this.cameraError.classList.add('hidden');
            document.getElementById('startCameraBtn').classList.add('hidden');
            document.getElementById('captureBtn').classList.remove('hidden');

        } catch (error) {
            console.error('Error accessing camera:', error);
            this.showCameraError('Unable to access camera. Please ensure you have granted camera permissions.');
        }
    }

    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
    }

    capturePhoto() {
        if (!this.stream) return;

        // Set canvas dimensions to match video
        this.canvas.width = this.video.videoWidth;
        this.canvas.height = this.video.videoHeight;

        // Draw video frame to canvas
        this.context.drawImage(this.video, 0, 0);

        // Convert canvas to data URL
        this.capturedImage = this.canvas.toDataURL('image/jpeg', 0.9);

        // Show captured image and controls
        this.video.classList.add('hidden');
        this.canvas.classList.remove('hidden');
        document.getElementById('captureBtn').classList.add('hidden');
        document.getElementById('retakeBtn').classList.remove('hidden');
        document.getElementById('uploadBtn').classList.remove('hidden');

        this.stopCamera();
    }

    retakePhoto() {
        this.capturedImage = null;
        this.canvas.classList.add('hidden');
        document.getElementById('retakeBtn').classList.add('hidden');
        document.getElementById('uploadBtn').classList.add('hidden');
        document.getElementById('startCameraBtn').classList.remove('hidden');
    }

    async uploadPhoto() {
        if (!this.capturedImage) {
            alert('No photo captured');
            return;
        }

        if (!this.gallerySelect.value) {
            alert('Please select a gallery');
            return;
        }

        const uploadProgress = document.getElementById('uploadProgress');
        const uploadProgressBar = document.getElementById('uploadProgressBar');

        try {
            // Show progress
            uploadProgress.classList.remove('hidden');
            uploadProgressBar.style.width = '10%';

            const formData = new FormData();
            formData.append('gallery_id', this.gallerySelect.value);
            formData.append('photo', this.capturedImage);
            formData.append('name', `Camera Upload ${new Date().toLocaleString()}`);

            uploadProgressBar.style.width = '30%';

            const response = await fetch('/camera-upload/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            uploadProgressBar.style.width = '70%';

            if (!response.ok) {
                throw new Error('Upload failed');
            }

            const result = await response.json();
            uploadProgressBar.style.width = '100%';

            if (result.success) {
                // Show success message
                alert(`Photo uploaded successfully to ${result.photo.gallery}!`);
                this.closeModal();

                // Optionally redirect to gallery or refresh page
                if (window.location.pathname.includes('/galleries/')) {
                    window.location.reload();
                }
            } else {
                throw new Error(result.message || 'Upload failed');
            }

        } catch (error) {
            console.error('Upload error:', error);
            alert('Failed to upload photo: ' + error.message);
        } finally {
            uploadProgress.classList.add('hidden');
            uploadProgressBar.style.width = '0%';
        }
    }

    showCameraError(message) {
        this.cameraError.classList.remove('hidden');
        this.cameraError.querySelector('p').textContent = message;
        this.video.classList.add('hidden');
    }

    resetUI() {
        // Reset all UI elements to initial state
        this.video.classList.add('hidden');
        this.canvas.classList.add('hidden');
        this.cameraError.classList.add('hidden');
        document.getElementById('startCameraBtn').classList.remove('hidden');
        document.getElementById('captureBtn').classList.add('hidden');
        document.getElementById('retakeBtn').classList.add('hidden');
        document.getElementById('uploadBtn').classList.add('hidden');
        document.getElementById('uploadProgress').classList.add('hidden');
        this.capturedImage = null;
    }
}

// Initialize camera upload when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('cameraUploadModal')) {
        new CameraUpload();
    }
});
