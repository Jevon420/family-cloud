<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\RegistrationRequestNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationRequestNotificationTest extends TestCase
{
    /**
     * Test the registration request notification.
     */
    public function testRegistrationRequestNotification()
    {
        // Create a valid user instance
        $user = User::factory()->create();

        // Trigger the notification
        Notification::send($user, new RegistrationRequestNotification($user));

        // Assert the email was sent
        $this->assertDatabaseHas('jobs', [
            'queue' => 'default',
        ]);
    }
}
