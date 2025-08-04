# Camera Upload Feature Documentation

## Overview
The Camera Upload feature allows users on mobile devices (and desktop with cameras) to take photos directly from their device's camera and upload them to galleries in the Family Cloud application.

## Features
- **Universal Access**: Camera icon appears in both desktop and mobile headers for authenticated users
- **Real-time Camera Access**: Uses device's camera through WebRTC API
- **Gallery Selection**: Users can select which gallery to upload photos to
- **Image Processing**: Photos are automatically converted to WebP format (with PNG fallback)
- **Thumbnail Generation**: Automatic thumbnail creation for efficient gallery browsing
- **Progress Feedback**: Visual upload progress indicator
- **Permission-based**: Respects user roles and gallery permissions

## How to Use

### For Mobile Users:
1. **Access**: Tap the camera icon in the mobile header (blue camera button)
2. **Select Gallery**: Choose the destination gallery from the dropdown
3. **Grant Permissions**: Allow camera access when prompted by the browser
4. **Start Camera**: Tap "Start Camera" to activate the camera preview
5. **Capture**: Tap "Capture Photo" when ready to take the picture
6. **Review**: Review the captured image
7. **Upload**: Tap "Upload" to save to the selected gallery
8. **Retake**: If needed, tap "Retake" to capture a new photo

### For Desktop Users:
1. **Access**: Click the camera icon in the desktop header
2. **Follow the same steps** as mobile users above

## Technical Details

### Browser Requirements
- Modern browsers supporting WebRTC (Chrome, Safari, Firefox, Edge)
- Camera access permissions granted by user
- JavaScript enabled

### File Processing
- **Primary Format**: WebP with 90% quality for optimal file size
- **Fallback Format**: PNG if WebP conversion fails
- **Thumbnail Size**: 400x400 pixels maximum (maintains aspect ratio)
- **Storage Location**: Wasabi cloud storage

### Permissions
- **Admin/Global Admin/Developer**: Can upload to any gallery
- **Family Users**: Can only upload to their own galleries
- **Authentication Required**: Only logged-in users can access the feature

### File Naming
- **Format**: `camera_upload_{timestamp}_{random_string}.webp`
- **Photo Name**: "Camera Upload - {date time}" (customizable)
- **Slug**: Generated from photo name for URL-friendly access

## API Endpoints

### Get Available Galleries
```
GET /camera-upload/galleries
```
Returns list of galleries the user can upload to.

### Upload Photo
```
POST /camera-upload/upload
```
Uploads captured photo to selected gallery.

**Parameters:**
- `gallery_id`: ID of the target gallery
- `photo`: Base64 encoded image data
- `name`: Optional custom name for the photo

## Error Handling
- **Camera Access Denied**: Shows error message with instructions
- **No Gallery Selected**: Prompts user to select a gallery
- **Upload Failures**: Displays specific error messages
- **Browser Compatibility**: Graceful fallback for unsupported browsers

## Security Features
- **CSRF Protection**: All requests include CSRF tokens
- **Authentication Required**: Must be logged in to access
- **Permission Validation**: Server-side gallery access validation
- **Input Validation**: Validates gallery existence and user permissions

## Mobile Optimization
- **Responsive Design**: Works seamlessly on mobile devices
- **Touch-friendly**: Large buttons optimized for touch interaction
- **Camera Switching**: Attempts to use rear camera on mobile devices
- **Orientation Support**: Works in both portrait and landscape modes

## Installation Notes
The feature is automatically available once:
1. The code is deployed
2. Assets are compiled (`npm run dev` or `npm run production`)
3. Users have appropriate permissions set up

## Troubleshooting

### Common Issues:
1. **Camera not accessible**: Ensure HTTPS is used (required for camera access)
2. **Upload fails**: Check storage configuration and Wasabi credentials
3. **No galleries visible**: Verify user has galleries created or appropriate permissions
4. **JavaScript errors**: Ensure all assets are compiled and loaded correctly

### Browser Permissions:
Users must grant camera access when prompted. If denied, they'll need to:
1. Click the camera icon in the browser address bar
2. Allow camera access
3. Refresh the page

## Future Enhancements
- Video recording support
- Multiple photo capture in one session
- Custom photo naming during capture
- GPS location tagging (with user consent)
- Photo filters and basic editing tools
