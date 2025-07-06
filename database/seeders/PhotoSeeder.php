<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Photo;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Str;

class PhotoSeeder extends Seeder
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

        // Get all galleries
        $galleries = Gallery::all();

        if ($galleries->isEmpty()) {
            $this->command->info('No galleries found. Please run gallery seeder first.');
            return;
        }

        // Sample photo data for each gallery
        foreach ($galleries as $gallery) {
            // Create 5-10 photos per gallery
            $photoCount = rand(5, 10);

            for ($i = 1; $i <= $photoCount; $i++) {
                $name = $gallery->name . ' - Photo ' . $i;

                $photo = Photo::create([
                    'user_id' => $user->id,
                    'gallery_id' => $gallery->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'file_path' => 'images/photos/' . Str::slug($gallery->name) . '-photo-' . $i . '.jpg',
                    'thumbnail_path' => 'images/photos/thumbnails/' . Str::slug($gallery->name) . '-photo-' . $i . '-thumb.jpg',
                    'description' => 'Beautiful memory from ' . $gallery->name,
                    'mime_type' => 'image/jpeg',
                    'file_size' => rand(500000, 2000000), // Random file size between 500KB and 2MB
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                // Create media visibility record
                $photo->visibility()->create([
                    'visibility' => 'public', // Match the gallery's visibility
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }
        }
    }
}
