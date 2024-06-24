<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all user IDs to assign badges to
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Create a collection of badges
        $badges = collect();

        foreach ($userIds as $userId) {
            $badges->push([
                'user_id' => $userId,
                'rfid' => Str::random(10),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert badges into the database
        DB::table('badges')->insert($badges->toArray());
    }
}
