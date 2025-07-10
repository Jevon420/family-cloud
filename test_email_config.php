<?php
// Script to test sending emails from each email configuration

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\EmailConfiguration;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Target email address
$targetEmail = 'jevon_redhead@yahoo.com';
echo "Testing email sending to: {$targetEmail}\n\n";

// Get the specific configuration (ID 4)
$configId = 3;
$config = EmailConfiguration::find($configId);

if (!$config) {
    echo "Email configuration with ID {$configId} not found!\n";
    exit(1);
}

echo "Testing configuration #{$configId}:\n";
echo "- Name: {$config->name}\n";
echo "- Email: {$config->email}\n";
echo "- SMTP Host: {$config->smtp_host}\n";
echo "- SMTP Port: {$config->smtp_port}\n";
echo "- SMTP Encryption: {$config->smtp_encryption}\n\n";

try {
    // Get the SMTP configuration
    $smtpConfig = $config->getSmtpConfig();

    // Create a custom mailer with this configuration
    $transport = new \Swift_SmtpTransport(
        $smtpConfig['host'],
        $smtpConfig['port'],
        $smtpConfig['encryption']
    );
    $transport->setUsername($smtpConfig['username']);
    $transport->setPassword($smtpConfig['password']);

    $swiftMailer = new \Swift_Mailer($transport);

    // Create email message
    $message = (new \Swift_Message('Test Email from ' . $config->name))
        ->setFrom([$config->email => $config->from_name ?: $config->name])
        ->setTo([$targetEmail])
        ->setBody('This is a test email from Family Cloud to verify mail configuration is working.');

    // Send the message
    $result = $swiftMailer->send($message);

    if ($result) {
        echo "Email sent successfully from {$config->email} to {$targetEmail}!\n";
    } else {
        echo "Failed to send email from {$config->email} to {$targetEmail}.\n";
    }

} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
}
