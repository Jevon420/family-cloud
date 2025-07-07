<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserApprovedSiteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $approvedBy;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, User $approvedBy)
    {
        $this->user = $user;
        $this->approvedBy = $approvedBy;
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
            ->subject('User Registration Approved - Family Cloud')
            ->greeting('Hello!')
            ->line('A user registration has been approved on Family Cloud.')
            ->line('User details:')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Approved By: ' . $this->approvedBy->name)
            ->line('Approval Date: ' . now()->format('Y-m-d H:i:s'))
            ->line('The user has been notified and provided with login credentials.')
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
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'approved_by' => $this->approvedBy->id,
        ];
    }
}
