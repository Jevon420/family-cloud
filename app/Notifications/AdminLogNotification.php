<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AdminLogNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $logDetails;

    public function __construct($logDetails)
    {
        $this->logDetails = $logDetails;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Log Entry')
                    ->line('A new log entry has been created:')
                    ->line($this->logDetails)
                    ->action('View Logs', url('/admin/logs'))
                    ->line('Thank you for using Family Cloud!');
    }

    public function toArray($notifiable)
    {
        return [
            'logDetails' => $this->logDetails,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'logDetails' => $this->logDetails,
        ]);
    }
}
