<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\Folder;
use App\Models\User;
use App\Models\SharedMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $shareLink = $media->generateShareableLink($request->expires_at);

        return response()->json([
            'success' => true,
            'share_link' => $shareLink,
            'expires_at' => $request->expires_at
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
}
