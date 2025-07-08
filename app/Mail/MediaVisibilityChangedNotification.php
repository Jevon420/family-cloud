<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MediaVisibilityChangedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mediaType;
    public $mediaName;
    public $owner;
    public $oldVisibility;
    public $newVisibility;
    public $shareLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mediaType, $mediaName, User $owner, $oldVisibility, $newVisibility, $shareLink = null)
    {
        $this->mediaType = $mediaType;
        $this->mediaName = $mediaName;
        $this->owner = $owner;
        $this->oldVisibility = $oldVisibility;
        $this->newVisibility = $newVisibility;
        $this->shareLink = $shareLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mediaTypeDisplay = ucfirst($this->mediaType);
        $subject = "Visibility Changed: Your {$mediaTypeDisplay} '{$this->mediaName}'";

        return $this->subject($subject)
                    ->view('emails.media-visibility-changed')
                    ->with([
                        'mediaType' => $this->mediaType,
                        'mediaName' => $this->mediaName,
                        'owner' => $this->owner,
                        'oldVisibility' => $this->oldVisibility,
                        'newVisibility' => $this->newVisibility,
                        'shareLink' => $this->shareLink
                    ]);
    }
}
