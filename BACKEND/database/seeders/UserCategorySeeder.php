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
                'category' => $category,
                'meal_discount' => $faker->randomElement([80.00, 90.00, 95.00, 100.00]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
