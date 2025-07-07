<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class OurTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            DB::table('our_team')->insert([
                'name' => $user->name,
                'profile_image' => $user->profile_image ?? 'default-profile.png',
                'position' => 'Team Member', // Default position, can be customized
                'bio' => 'This is a short bio about ' . $user->name . '.',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/' . $user->id,
                    'twitter' => 'https://twitter.com/' . $user->id,
                    'linkedin' => 'https://linkedin.com/in/' . $user->id,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
