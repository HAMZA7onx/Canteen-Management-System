<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Seeder5_Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'category_id' => 1,
                'affected_categories' => json_encode([1, 2]),
                'editor' => 'System',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone_number' => '1234567890',
                'gender' => 'male',
                'api_token' => Str::random(80),
                'email_verified_at' => Carbon::now(),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 2,
                'affected_categories' => json_encode([1, 3]),
                'editor' => 'System',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone_number' => '0987654321',
                'gender' => 'female',
                'api_token' => Str::random(80),
                'email_verified_at' => Carbon::now(),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 3,
                'affected_categories' => json_encode([2, 3]),
                'editor' => 'System',
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'phone_number' => null,
                'gender' => 'female',
                'api_token' => Str::random(80),
                'email_verified_at' => Carbon::now(),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
