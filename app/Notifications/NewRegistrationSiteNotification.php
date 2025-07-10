<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class NewRegistrationSiteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $developerEmails = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'Developer');
        })->pluck('email')->toArray();

        $globalAdminEmails = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'Global Admin');
        })->pluck('email')->toArray();

        $recipients = array_merge($developerEmails, $globalAdminEmails, [config('mail.from.address')]);

        \Mail::to($recipients)->send(new \App\Mail\NewRegistrationSiteMail($this->user));

        return (new MailMessage)
            ->subject('New User Registration - Family Cloud')
            ->line('The notification has been sent to the relevant recipients.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ];
    }
}
