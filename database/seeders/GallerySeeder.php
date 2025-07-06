<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Str;

class GallerySeeder extends Seeder
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

        // Create galleries
        $galleries = [
            [
                'name' => 'Family Vacation 2024',
                'description' => 'Our amazing family trip to the mountains.',
                'cover_image' => 'images/galleries/vacation-2024.jpg',
                'visibility' => 'public',
            ],
            [
                'name' => 'Holiday Celebrations',
                'description' => 'Christmas, Thanksgiving, and other holiday gatherings.',
                'cover_image' => 'images/galleries/holidays.jpg',
                'visibility' => 'public',
            ],
            [
                'name' => 'Baby\'s First Year',
                'description' => 'Memorable moments from the first year of our newest family member.',
                'cover_image' => 'images/galleries/baby-first-year.jpg',
                'visibility' => 'public',
            ],
            [
                'name' => 'Graduation Day',
                'description' => 'Celebrating academic achievements and milestones.',
                'cover_image' => 'images/galleries/graduation.jpg',
                'visibility' => 'public',
            ],
            [
                'name' => 'Family Reunion 2023',
                'description' => 'Everyone together for the first time in years!',
                'cover_image' => 'images/galleries/reunion-2023.jpg',
                'visibility' => 'public',
            ],
        ];

        foreach ($galleries as $galleryData) {
            $gallery = Gallery::create([
                'user_id' => $user->id,
                'name' => $galleryData['name'],
                'slug' => Str::slug($galleryData['name']),
                'description' => $galleryData['description'],
                'cover_image' => $galleryData['cover_image'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Create media visibility record
            $gallery->visibility()->create([
                'visibility' => $galleryData['visibility'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }
    }
}
