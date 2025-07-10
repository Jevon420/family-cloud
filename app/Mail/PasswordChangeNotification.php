<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PasswordChangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Changed Successfully')
                    ->view('emails.password-change-notification')
                    ->with(['user' => $this->user]);
    }

    /**
     * Send notifications to user, developer, global admin, and site mail.
     */
    public static function sendNotifications($user)
    {
        // Send email to the user
        Mail::to($user->email)->send(new self($user));
    }

    /**
     * Send a notification email to site mail indicating a user has changed their password.
     */
    public static function sendSiteNotification($user)
    {
        $siteEmail = 'updates@jevonredhead.com';
        $fromEmail = config('mail.from.address');

        Mail::mailer('smtp')->to($siteEmail)->send(new self($user));
    }
}
