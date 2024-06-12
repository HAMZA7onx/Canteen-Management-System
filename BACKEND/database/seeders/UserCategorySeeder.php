<?php

namespace Database\Seeders;

use App\Models\UserCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $categories = [
            'Student',
            'Teacher',
            'Staff',
            'Alumni',
            'Guest',
        ];

        foreach ($categories as $category) {
            UserCategory::create([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
