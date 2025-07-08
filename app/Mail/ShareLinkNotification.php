<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ShareLinkNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mediaType;
    public $mediaName;
    public $sharedBy;
    public $shareLink;
    public $expiresAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mediaType, $mediaName, User $sharedBy, $shareLink, $expiresAt = null)
    {
        $this->mediaType = $mediaType;
        $this->mediaName = $mediaName;
        $this->sharedBy = $sharedBy;
        $this->shareLink = $shareLink;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mediaTypeDisplay = ucfirst($this->mediaType);
        $subject = "{$this->sharedBy->name} shared a {$mediaTypeDisplay} link with you";

        return $this->subject($subject)
                    ->view('emails.share-link')
                    ->with([
                        'mediaType' => $this->mediaType,
                        'mediaName' => $this->mediaName,
                        'sharedBy' => $this->sharedBy,
                        'shareLink' => $this->shareLink,
                        'expiresAt' => $this->expiresAt
                    ]);
    }
}
