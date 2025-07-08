<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MediaSharedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mediaType;
    public $mediaName;
    public $sharedBy;
    public $shareLink;
    public $permissions;
    public $expiresAt;
    public $isPublic;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mediaType, $mediaName, $sharedBy, $shareLink, $permissions = [], $expiresAt = null, $isPublic = false)
    {
        $this->mediaType = $mediaType;
        $this->mediaName = $mediaName;
        $this->sharedBy = $sharedBy;
        $this->shareLink = $shareLink;
        $this->permissions = $permissions;
        $this->expiresAt = $expiresAt;
        $this->isPublic = $isPublic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mediaTypeDisplay = ucfirst($this->mediaType);
        $subject = "{$this->sharedBy->name} shared a {$mediaTypeDisplay} with you";

        return $this->subject($subject)
                    ->view('emails.media-shared')
                    ->with([
                        'mediaType' => $this->mediaType,
                        'mediaName' => $this->mediaName,
                        'sharedBy' => $this->sharedBy,
                        'shareLink' => $this->shareLink,
                        'permissions' => $this->permissions,
                        'expiresAt' => $this->expiresAt,
                        'isPublic' => $this->isPublic
                    ]);
    }
}
