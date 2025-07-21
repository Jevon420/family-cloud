<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LowStorageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $storageDetails;

    public function __construct($storageDetails)
    {
        $this->storageDetails = $storageDetails;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Low Storage Warning')
                    ->line('Your storage is running low:')
                    ->line($this->storageDetails)
                    ->action('Upgrade Storage', url('/settings/storage'))
                    ->line('Thank you for using Family Cloud!');
    }

    public function toArray($notifiable)
    {
        return [
            'storageDetails' => $this->storageDetails,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'storageDetails' => $this->storageDetails,
        ]);
    }
}
