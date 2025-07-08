<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MediaVisibility;
use Illuminate\Support\Str;

class FixMissingTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sharing:fix-missing-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix any MediaVisibility records that are missing share tokens';

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
        $this->info('Checking for MediaVisibility records without share tokens...');

        $count = 0;

        // Find all MediaVisibility records where the share_token is null or empty
        $records = MediaVisibility::where(function($query) {
            $query->whereNull('share_token')
                  ->orWhere('share_token', '');
        })->get();

        $this->info(sprintf('Found %d records with missing share tokens.', $records->count()));

        if ($records->count() > 0) {
            $bar = $this->output->createProgressBar($records->count());
            $bar->start();

            foreach ($records as $record) {
                // Generate a new share token
                $record->share_token = Str::random(32);
                $record->save();

                $count++;
                $bar->advance();
            }

            $bar->finish();
            $this->info('');
            $this->info(sprintf('Fixed %d records with missing share tokens.', $count));
        } else {
            $this->info('No records found with missing share tokens.');
        }

        return 0;
    }
}
