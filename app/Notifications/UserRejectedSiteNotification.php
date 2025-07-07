<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserRejectedSiteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $userData;
    protected $rejectedBy;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $userData, User $rejectedBy)
    {
        $this->userData = $userData;
        $this->rejectedBy = $rejectedBy;
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
        return (new MailMessage)
            ->subject('User Registration Rejected - Family Cloud')
            ->greeting('Hello!')
            ->line('A user registration has been rejected on Family Cloud.')
            ->line('User details:')
            ->line('Name: ' . $this->userData['name'])
            ->line('Email: ' . $this->userData['email'])
            ->line('Rejected By: ' . $this->rejectedBy->name)
            ->line('Rejection Date: ' . now()->format('Y-m-d H:i:s'))
            ->line('The user has been notified of this decision.')
            ->line('This is an automated notification from the Family Cloud system.');
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
            'user_data' => $this->userData,
            'rejected_by' => $this->rejectedBy->id,
        ];
    }
}
