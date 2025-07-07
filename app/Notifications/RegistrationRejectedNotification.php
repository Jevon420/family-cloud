<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class RegistrationRejectedNotification extends Notification implements ShouldQueue
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
            ->subject('Registration Request Not Approved - Family Cloud')
            ->greeting('Hello ' . $this->userData['name'] . ',')
            ->line('We regret to inform you that your registration request for Family Cloud has not been approved at this time.')
            ->line('If you believe this is an error or have questions, please contact the administrator.')
            ->line('Thank you for your interest in Family Cloud.');
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
