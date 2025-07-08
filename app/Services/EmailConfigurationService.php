<?php

namespace App\Services;

use App\Models\EmailConfiguration;

class EmailConfigurationService
{
    /**
     * Configure the mailer to use the support email configuration
     *
     * @return EmailConfiguration|null
     */
    public static function configureSupportEmail()
    {
        $supportEmailConfig = EmailConfiguration::where('email', 'support@jevonredhead.com')->first();

        if ($supportEmailConfig) {
            config([
                'mail.mailers.smtp.host' => $supportEmailConfig->smtp_host,
                'mail.mailers.smtp.port' => $supportEmailConfig->smtp_port,
                'mail.mailers.smtp.encryption' => $supportEmailConfig->smtp_encryption,
                'mail.mailers.smtp.username' => $supportEmailConfig->smtp_username ?: $supportEmailConfig->email,
                'mail.mailers.smtp.password' => $supportEmailConfig->password,
                'mail.from.address' => $supportEmailConfig->email,
                'mail.from.name' => $supportEmailConfig->name ?: config('app.name'),
            ]);
        }

        return $supportEmailConfig;
    }

    /**
     * Get the support email configuration
     *
     * @return EmailConfiguration|null
     */
    public static function getSupportEmailConfig()
    {
        return EmailConfiguration::where('email', 'support@jevonredhead.com')->first();
    }
}
