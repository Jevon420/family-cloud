<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AccountApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
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
        $loginUrl = url(route('login'));

        return (new MailMessage)
            ->subject('Your Account Has Been Approved - Family Cloud')
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('Good news! Your Family Cloud account has been approved.')
            ->line('You can now log in using the following credentials:')
            ->line('Email: ' . $this->user->email)
            ->line('Password: ' . $this->password)
            ->line('**Important:** You will be required to change your password on your first login.')
            ->action('Login Now', $loginUrl)
            ->line('Thank you for joining Family Cloud!');
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
        ];
    }
}
