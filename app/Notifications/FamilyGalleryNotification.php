<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FamilyGalleryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $galleryDetails;

    public function __construct($galleryDetails)
    {
        $this->galleryDetails = $galleryDetails;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Gallery Created')
                    ->line('A new gallery has been created:')
                    ->line($this->galleryDetails)
                    ->action('View Gallery', url('/family/galleries'))
                    ->line('Thank you for using Family Cloud!');
    }

    public function toArray($notifiable)
    {
        return [
            'galleryDetails' => $this->galleryDetails,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'galleryDetails' => $this->galleryDetails,
        ]);
    }
}
