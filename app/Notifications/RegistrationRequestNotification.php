<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class RegistrationRequestNotification extends Notification implements ShouldQueue
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
        $userManagementUrl = url(route('admin.settings.users')) . '?from_email=1';
        $approveUrl = url(route('admin.users.approve', $this->user->id));
        $rejectUrl = url(route('admin.users.reject', $this->user->id));

        return (new MailMessage)
            ->subject('New User Registration Request - Family Cloud')
            ->greeting('Hello Admin!')
            ->line('A new user has requested to join Family Cloud.')
            ->line('User details:')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Registration Date: ' . $this->user->created_at->format('M d, Y H:i'))
            ->line('Please review this registration request and take appropriate action.')
            ->action('View User Management', $userManagementUrl)
            ->line('You can also use the direct action links below:')
            ->action('Approve User', $approveUrl)
            ->line('Or click here to reject: ' . $rejectUrl)
            ->line('Thank you for managing Family Cloud!');
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
