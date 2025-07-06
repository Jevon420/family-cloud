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
                'is_public' => false,
            ],
            [
                'name' => 'Videos',
                'description' => 'Family videos and recordings',
                'visibility' => 'private',
                'is_public' => false,
            ],
            [
                'name' => 'Recipes',
                'description' => 'Family recipes collection',
                'visibility' => 'public',
                'is_public' => true,
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel documents and memories',
                'visibility' => 'public',
                'is_public' => true,
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
                'visibility' => $folderData['visibility'],
                'is_public' => $folderData['is_public'],
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
                    Folder::create([
                        'user_id' => $user->id,
                        'parent_id' => $parentFolder->id,
                        'name' => $subfolderName,
                        'slug' => Str::slug($subfolderName),
                        'description' => $subfolderName . ' folder under ' . $parentFolder->name,
                        'visibility' => $parentFolder->visibility,
                        'is_public' => $parentFolder->is_public,
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);
                }
            }
        }
    }
}
