<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailConfiguration;

class EmailConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configurations = [
            [
                'name' => 'Support Email',
                'email' => 'support@jevonredhead.com',
                'type' => 'both',
                'is_default' => true,
                'is_active' => true,
                'smtp_host' => 'smtp.hostinger.com',
                'smtp_port' => 465,
                'smtp_encryption' => 'ssl',
                'smtp_username' => 'support@jevonredhead.com',
                'imap_host' => 'imap.hostinger.com',
                'imap_port' => 993,
                'imap_encryption' => 'ssl',
                'imap_username' => 'support@jevonredhead.com',
                'pop_host' => 'pop.hostinger.com',
                'pop_port' => 995,
                'pop_encryption' => 'ssl',
                'pop_username' => 'support@jevonredhead.com',
                'from_name' => 'Family Cloud Support',
                'signature' => '<br><br>Best regards,<br>Family Cloud Support Team<br>support@jevonredhead.com',
                'settings' => [
                    'auto_reply' => false,
                    'forward_to' => null,
                    'retention_days' => 365,
                ],
            ],
            [
                'name' => 'Updates Email',
                'email' => 'updates@jevonredhead.com',
                'type' => 'outgoing',
                'is_default' => false,
                'is_active' => true,
                'smtp_host' => 'smtp.hostinger.com',
                'smtp_port' => 465,
                'smtp_encryption' => 'ssl',
                'smtp_username' => 'updates@jevonredhead.com',
                'from_name' => 'Family Cloud Updates',
                'signature' => '<br><br>Stay updated!<br>Family Cloud Team<br>updates@jevonredhead.com',
                'settings' => [
                    'auto_reply' => false,
                    'forward_to' => null,
                    'retention_days' => 90,
                ],
            ],
            [
                'name' => 'Contact Email',
                'email' => 'contact@jevonredhead.com',
                'type' => 'both',
                'is_default' => false,
                'is_active' => true,
                'smtp_host' => 'smtp.hostinger.com',
                'smtp_port' => 465,
                'smtp_encryption' => 'ssl',
                'smtp_username' => 'contact@jevonredhead.com',
                'imap_host' => 'imap.hostinger.com',
                'imap_port' => 993,
                'imap_encryption' => 'ssl',
                'imap_username' => 'contact@jevonredhead.com',
                'pop_host' => 'pop.hostinger.com',
                'pop_port' => 995,
                'pop_encryption' => 'ssl',
                'pop_username' => 'contact@jevonredhead.com',
                'from_name' => 'Family Cloud Contact',
                'signature' => '<br><br>Thank you for contacting us!<br>Family Cloud Team<br>contact@jevonredhead.com',
                'settings' => [
                    'auto_reply' => true,
                    'auto_reply_subject' => 'Thank you for contacting us',
                    'auto_reply_body' => 'We have received your message and will respond within 24 hours.',
                    'forward_to' => 'support@jevonredhead.com',
                    'retention_days' => 180,
                ],
            ],
            [
                'name' => 'No Reply Email',
                'email' => 'no-reply@jevonredhead.com',
                'type' => 'outgoing',
                'is_default' => false,
                'is_active' => true,
                'smtp_host' => 'smtp.hostinger.com',
                'smtp_port' => 465,
                'smtp_encryption' => 'ssl',
                'smtp_username' => 'no-reply@jevonredhead.com',
                'from_name' => 'Family Cloud (No Reply)',
                'signature' => '<br><br>This is an automated message. Please do not reply to this email.<br>Family Cloud System',
                'settings' => [
                    'auto_reply' => false,
                    'forward_to' => null,
                    'retention_days' => 30,
                ],
            ],
        ];

        foreach ($configurations as $config) {
            EmailConfiguration::create($config);
        }
    }
}
