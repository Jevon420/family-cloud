<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Str;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get developer user
        $user = User::where('email', 'jevonredhead@boostbyte.dev')->first();

        if (!$user) {
            $user = User::first();
        }

        // Get all folders
        $folders = Folder::all();

        if ($folders->isEmpty()) {
            $this->command->info('No folders found. Please run folder seeder first.');
            return;
        }

        // File types based on folder names
        $fileTypes = [
            'Documents' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'document_'],
                ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'prefix' => 'report_'],
            ],
            'Personal' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'personal_'],
                ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'prefix' => 'letter_'],
            ],
            'Financial' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'statement_'],
                ['ext' => 'xlsx', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'prefix' => 'budget_'],
            ],
            'Medical' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'medical_'],
                ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'prefix' => 'health_'],
            ],
            'Education' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'certificate_'],
                ['ext' => 'pptx', 'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'prefix' => 'presentation_'],
            ],
            'Videos' => [
                ['ext' => 'mp4', 'mime' => 'video/mp4', 'prefix' => 'video_'],
                ['ext' => 'mov', 'mime' => 'video/quicktime', 'prefix' => 'movie_'],
            ],
            'Recipes' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'recipe_'],
                ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'prefix' => 'cookbook_'],
            ],
            'Travel' => [
                ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'itinerary_'],
                ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'prefix' => 'travel_'],
            ],
        ];

        // Generic file types for any folder that doesn't have specific types
        $genericFileTypes = [
            ['ext' => 'pdf', 'mime' => 'application/pdf', 'prefix' => 'file_'],
            ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'prefix' => 'document_'],
            ['ext' => 'xlsx', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'prefix' => 'spreadsheet_'],
            ['ext' => 'jpg', 'mime' => 'image/jpeg', 'prefix' => 'image_'],
        ];

        // Create files for each folder
        foreach ($folders as $folder) {
            // Determine how many files to create (2-5 per folder)
            $fileCount = rand(2, 5);

            // Get file types for this folder
            $types = isset($fileTypes[$folder->name]) ? $fileTypes[$folder->name] : $genericFileTypes;

            for ($i = 1; $i <= $fileCount; $i++) {
                // Pick a random file type
                $fileType = $types[array_rand($types)];

                $name = $fileType['prefix'] . rand(1000, 9999) . '.' . $fileType['ext'];

                File::create([
                    'user_id' => $user->id,
                    'folder_id' => $folder->id,
                    'name' => $name,
                    'slug' => Str::slug(pathinfo($name, PATHINFO_FILENAME)),
                    'file_path' => 'files/' . Str::slug($folder->name) . '/' . $name,
                    'description' => 'A ' . $fileType['ext'] . ' file in the ' . $folder->name . ' folder',
                    'visibility' => $folder->visibility,
                    'is_public' => $folder->is_public,
                    'mime_type' => $fileType['mime'],
                    'file_size' => rand(100000, 5000000), // Random file size between 100KB and 5MB
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }
        }
    }
}
