<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CameraUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get galleries that the user can upload to
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGalleries()
    {
        $user = Auth::user();

        // Get galleries based on user role
        if ($user->hasRole('Admin') || $user->hasRole('Global Admin') || $user->hasRole('Developer')) {
            $galleries = Gallery::select('id', 'name', 'slug')->orderBy('name')->get();
        } else {
            // Family users can only upload to their own galleries or galleries they have permission to
            $galleries = Gallery::where('user_id', $user->id)
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->get();
        }

        return response()->json([
            'success' => true,
            'galleries' => $galleries
        ]);
    }

    /**
     * Upload photo from camera
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'gallery_id' => 'required|exists:galleries,id',
            'photo' => 'required|string', // Base64 encoded image
            'name' => 'string|max:255',
        ]);

        try {
            $gallery = Gallery::findOrFail($request->gallery_id);

            // Check if user can upload to this gallery
            $user = Auth::user();
            if (!$user->hasRole(['Admin', 'Global Admin', 'Developer']) && $gallery->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to upload to this gallery.'
                ], 403);
            }

            // Decode base64 image
            $photoData = $request->photo;
            if (strpos($photoData, 'data:image/') === 0) {
                $photoData = substr($photoData, strpos($photoData, ',') + 1);
            }
            $decodedImage = base64_decode($photoData);

            if (!$decodedImage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image data.'
                ], 400);
            }

            // Generate filename
            $filename = 'camera_upload_' . time() . '_' . Str::random(8);
            $photoPath = "familycloud/family/galleries/{$gallery->slug}/photos/{$filename}.webp";

            // Process image with Intervention Image
            try {
                $img = Image::make($decodedImage);
                $img->encode('webp', 90);
                Storage::disk('wasabi')->put($photoPath, $img->__toString());
                $mimeType = 'image/webp';
                $fileSize = strlen($img->__toString());
            } catch (\Exception $e) {
                try {
                    $photoPath = "familycloud/family/galleries/{$gallery->slug}/photos/{$filename}.png";
                    $img = Image::make($decodedImage);
                    $img->encode('png', 90);
                    Storage::disk('wasabi')->put($photoPath, $img->__toString());
                    $mimeType = 'image/png';
                    $fileSize = strlen($img->__toString());
                } catch (\Exception $e2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to process image.'
                    ], 500);
                }
            }

            // Create thumbnail
            $thumbnailPath = "familycloud/family/galleries/{$gallery->slug}/photos/thumbnails/{$filename}.webp";
            $thumbnailSize = 0;
            try {
                $thumbnail = Image::make($decodedImage)
                    ->fit(400, 400, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->encode('webp', 90);
                Storage::disk('wasabi')->put($thumbnailPath, $thumbnail->__toString());
                $thumbnailSize = strlen($thumbnail->__toString());
            } catch (\Exception $e) {
                try {
                    $thumbnailPath = "familycloud/family/galleries/{$gallery->slug}/photos/thumbnails/{$filename}.png";
                    $thumbnail = Image::make($decodedImage)
                        ->fit(400, 400, function ($constraint) {
                            $constraint->upsize();
                        })
                        ->encode('png', 90);
                    Storage::disk('wasabi')->put($thumbnailPath, $thumbnail->__toString());
                    $thumbnailSize = strlen($thumbnail->__toString());
                } catch (\Exception $e2) {
                    // Thumbnail creation failed, but main image succeeded
                    $thumbnailPath = null;
                    $thumbnailSize = 0;
                }
            }

            // Create photo record
            $photo = new Photo();
            $photo->user_id = $user->id;
            $photo->gallery_id = $gallery->id;
            $photo->slug = Str::slug($request->name ?? 'Camera Upload') . '-' . Str::random(5);
            $photo->name = $request->name ?? 'Camera Upload - ' . now()->format('Y-m-d H:i:s');
            $photo->file_path = $photoPath;
            $photo->thumbnail_path = $thumbnailPath;
            $photo->thumbnail_size = $thumbnailSize;
            $photo->mime_type = $mimeType;
            $photo->file_size = $fileSize;
            $photo->created_by = $user->id;
            $photo->updated_by = $user->id;
            $photo->save();

            // Create visibility record
            $galleryVisibility = optional($gallery->visibility)->visibility ?? 'private';
            $photo->visibility()->create([
                'visibility' => $galleryVisibility,
                'created_by' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully!',
                'photo' => [
                    'id' => $photo->id,
                    'name' => $photo->name,
                    'gallery' => $gallery->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the photo: ' . $e->getMessage()
            ], 500);
        }
    }
}
