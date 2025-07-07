<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailConfiguration;
use Illuminate\Support\Str;

class ListEmailConfigurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:list-configurations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all email configurations in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Email Configurations:');
        $this->line('');

        $emailConfigs = EmailConfiguration::all();

        if ($emailConfigs->isEmpty()) {
            $this->error('No email configurations found in the database.');
            return 1;
        }

        $headers = ['ID', 'Name', 'Email', 'Type', 'Active', 'Default', 'SMTP Host', 'SMTP Port', 'SMTP Encryption', 'SMTP Username', 'Has Password'];

        $rows = [];
        foreach ($emailConfigs as $config) {
            $rows[] = [
                'id' => $config->id,
                'name' => $config->name,
                'email' => $config->email,
                'type' => $config->type,
                'active' => $config->is_active ? 'Yes' : 'No',
                'default' => $config->is_default ? 'Yes' : 'No',
                'smtp_host' => $config->smtp_host,
                'smtp_port' => $config->smtp_port,
                'smtp_encryption' => $config->smtp_encryption,
                'smtp_username' => $config->smtp_username ?: $config->email,
                'has_password' => !empty($config->password) ? 'Yes' : 'No',
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
