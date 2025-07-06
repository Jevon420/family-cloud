<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Str;

class FolderSeeder extends Seeder
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

        // Create main folders
        $mainFolders = [
            [
                'name' => 'Documents',
                'description' => 'Important family documents',
                'visibility' => 'private',
            ],
            [
                'name' => 'Videos',
                'description' => 'Family videos and recordings',
                'visibility' => 'private',
            ],
            [
                'name' => 'Recipes',
                'description' => 'Family recipes collection',
                'visibility' => 'public',
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel documents and memories',
                'visibility' => 'public',
            ],
        ];

        $createdFolders = [];

        foreach ($mainFolders as $folderData) {
            $folder = Folder::create([
                'user_id' => $user->id,
                'parent_id' => null,
                'name' => $folderData['name'],
                'slug' => Str::slug($folderData['name']),
                'description' => $folderData['description'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Create media visibility record
            $folder->visibility()->create([
                'visibility' => $folderData['visibility'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            $createdFolders[] = $folder;
        }

        // Create subfolders for each main folder
        $subfolders = [
            'Documents' => ['Personal', 'Financial', 'Medical', 'Education'],
            'Videos' => ['Birthdays', 'Weddings', 'Vacations', 'Celebrations'],
            'Recipes' => ['Desserts', 'Main Dishes', 'Appetizers', 'Holiday Specials'],
            'Travel' => ['Europe', 'Asia', 'North America', 'South America'],
        ];

        foreach ($createdFolders as $parentFolder) {
            if (isset($subfolders[$parentFolder->name])) {
                foreach ($subfolders[$parentFolder->name] as $subfolderName) {
                    $subfolder = Folder::create([
                        'user_id' => $user->id,
                        'parent_id' => $parentFolder->id,
                        'name' => $subfolderName,
                        'slug' => Str::slug($subfolderName),
                        'description' => $subfolderName . ' folder under ' . $parentFolder->name,
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);

                    // Create media visibility record with same visibility as parent
                    $subfolder->visibility()->create([
                        'visibility' => $parentFolder->visibility->visibility,
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);
                }
            }
        }
    }
}
