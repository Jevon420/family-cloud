<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\Folder;
use App\Models\User;
use App\Models\SharedMedia;
use App\Models\MediaVisibility;
use App\Mail\MediaSharedNotification;
use App\Mail\MediaVisibilityChangedNotification;
use App\Mail\ShareLinkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SharingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function shareWithUser(Request $request)
    {
        $request->validate([
            'media_type' => 'required|in:file,photo,gallery,folder',
            'media_id' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array',
            'permissions.*' => 'in:view,download,edit',
            'expires_at' => 'nullable|date|after:now',
            'send_notification' => 'nullable|boolean',
        ]);

        $mediaClass = $this->getMediaClass($request->media_type);
        $media = $mediaClass::where('id', $request->media_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $permissions = $request->permissions ?: ['view'];

        $sharedMedia = $media->shareWithUser(
            $request->user_id,
            $permissions,
            $request->expires_at
        );

        // Send notification if requested
        if ($request->send_notification ?? true) {
            $user = User::find($request->user_id);

            // Create a share link for the user
            $shareLink = route('shared.media', ['token' => $sharedMedia->share_token]);

            Mail::to($user->email)->send(new MediaSharedNotification(
                $this->getMediaTypeString($request->media_type),
                $media->name,
                auth()->user(),
                $shareLink,
                $permissions,
                $request->expires_at,
                $media->isPublic()
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Media shared successfully',
            'share_id' => $sharedMedia->id
        ]);
    }

    public function generateShareLink(Request $request)
    {
        $request->validate([
            'media_type' => 'required|in:file,photo,gallery,folder',
            'media_id' => 'required|integer',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $mediaClass = $this->getMediaClass($request->media_type);
        $media = $mediaClass::where('id', $request->media_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // If visibility doesn't exist or is private, create a link visibility
        // For public items, just return the existing link
        $visibility = $media->visibility;
        $wasPrivate = !$visibility || $visibility->visibility === 'private';

        $shareLink = $media->generateShareableLink($request->expires_at);

        return response()->json([
            'success' => true,
            'share_link' => $shareLink,
            'expires_at' => $request->expires_at,
            'visibility_type' => $media->visibility->visibility
        ]);
    }

    public function removeShare(Request $request, $shareId)
    {
        $share = SharedMedia::where('id', $shareId)
            ->where('shared_by', auth()->id())
            ->firstOrFail();

        $share->delete();

        return response()->json([
            'success' => true,
            'message' => 'Share removed successfully'
        ]);
    }

    public function getSharedUsers(Request $request)
    {
        $request->validate([
            'media_type' => 'required|in:file,photo,gallery,folder',
            'media_id' => 'required|integer',
        ]);

        $mediaClass = $this->getMediaClass($request->media_type);
        $media = $mediaClass::where('id', $request->media_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $sharedUsers = $media->sharedMedia()
            ->with('sharedWith:id,name,email')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();

        return response()->json([
            'success' => true,
            'shared_users' => $sharedUsers
        ]);
    }

    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $users = User::where('id', '!=', auth()->id())
            ->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query . '%')
                      ->orWhere('email', 'like', '%' . $request->query . '%');
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function showSharedLink($token)
    {
        $visibility = \App\Models\MediaVisibility::where('share_token', $token)
            ->where('visibility', 'link')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $media = $visibility->media;

        return view('shared.link', compact('media', 'visibility'));
    }

    public function getMediaVisibility(Request $request)
    {
        $request->validate([
            'media_type' => 'required|in:file,photo,gallery,folder',
            'media_id' => 'required|integer',
        ]);

        $mediaClass = $this->getMediaClass($request->media_type);
        $media = $mediaClass::findOrFail($request->media_id);

        // Check if the user is authorized to view this media
        if ($media->user_id !== auth()->id() && !$media->isSharedWithUser(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // If visibility record doesn't exist, create one
        if (!$media->visibility) {
            $visibility = $media->visibility()->create([
                'visibility' => 'private',
                'created_by' => auth()->id(),
            ]);
            $media->refresh();
        } else {
            $visibility = $media->visibility;
        }

        return response()->json([
            'success' => true,
            'visibility' => $visibility
        ]);
    }

    public function updateVisibility(Request $request)
    {
        $request->validate([
            'media_type' => 'required|in:file,photo,gallery,folder',
            'media_id' => 'required|integer',
            'visibility' => 'required|in:private,public',
            'notify_owner' => 'nullable|boolean'
        ]);

        $mediaClass = $this->getMediaClass($request->media_type);
        $media = $mediaClass::where('id', $request->media_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $oldVisibility = $media->visibility ? $media->visibility->visibility : 'private';
        $newVisibility = $request->visibility;

        // If changing from public to private, regenerate the share token for security
        $shareToken = ($oldVisibility === 'public' && $newVisibility === 'private')
            ? Str::random(32)
            : ($media->visibility ? $media->visibility->share_token : Str::random(32));

        $media->visibility()->updateOrCreate(
            ['media_type' => get_class($media), 'media_id' => $media->id],
            [
                'visibility' => $newVisibility,
                'share_token' => $shareToken,
                'updated_by' => auth()->id(),
            ]
        );

        // Send notification if requested or if there's a significant change
        if (($request->notify_owner ?? true) && $oldVisibility !== $newVisibility) {
            $shareLink = null;
            if ($newVisibility === 'public') {
                $shareLink = route('shared.link', $shareToken);
            }

            Mail::to($media->user->email)->send(new MediaVisibilityChangedNotification(
                $this->getMediaTypeString($request->media_type),
                $media->name,
                $media->user,
                $oldVisibility,
                $newVisibility,
                $shareLink
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Visibility updated successfully',
            'visibility' => $newVisibility,
            'token_regenerated' => ($oldVisibility === 'public' && $newVisibility === 'private'),
            'share_link' => $newVisibility === 'public' ? route('shared.link', $shareToken) : null
        ]);
    }    public function emailShareLink(Request $request)
    {
        $request->validate([
            'media_type' => 'required|in:file,photo,gallery,folder',
            'media_id' => 'required|integer',
            'email' => 'required|email',
            'share_link' => 'required|url',
        ]);

        $mediaClass = $this->getMediaClass($request->media_type);
        $media = $mediaClass::where('id', $request->media_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Try to find if the email belongs to a registered user
        $user = User::where('email', $request->email)->first();

        // Get expiration from visibility if it exists
        $expiresAt = null;
        if ($media->visibility && $media->visibility->expires_at) {
            $expiresAt = $media->visibility->expires_at;
        }

        // Send email
        Mail::to($request->email)->send(new ShareLinkNotification(
            $this->getMediaTypeString($request->media_type),
            $media->name,
            auth()->user(),
            $request->share_link,
            $expiresAt
        ));

        // If this is a registered user and not already shared with them,
        // also create a SharedMedia record for better tracking
        if ($user && !$media->isSharedWithUser($user->id)) {
            $media->shareWithUser(
                $user->id,
                ['view', 'download'],
                $expiresAt
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Share link sent successfully'
        ]);
    }

    public function showSharedMedia($token)
    {
        $sharedMedia = SharedMedia::where('share_token', $token)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $media = $sharedMedia->media;
        $sharedBy = $sharedMedia->sharedBy;
        $permissions = $sharedMedia->permissions;

        return view('shared.media', compact('media', 'sharedMedia', 'sharedBy', 'permissions'));
    }

    private function getMediaClass($mediaType)
    {
        switch ($mediaType) {
            case 'file':
                return File::class;
            case 'photo':
                return Photo::class;
            case 'gallery':
                return Gallery::class;
            case 'folder':
                return Folder::class;
            default:
                abort(400, 'Invalid media type');
        }
    }

    /**
     * Convert media type to user-friendly string
     */
    private function getMediaTypeString($mediaType)
    {
        switch ($mediaType) {
            case 'file':
                return 'file';
            case 'photo':
                return 'photo';
            case 'gallery':
                return 'gallery';
            case 'folder':
                return 'folder';
            default:
                return 'media';
        }
    }
}
