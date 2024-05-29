<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Create some user categories
        $userCategories = UserCategory::all();

        // Create fake users
        foreach (range(1, 50) as $index) {
            User::create([
                'category_id' => $userCategories->random()->id,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone_number' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['female', 'male']),
                'api_token' => $faker->unique()->uuid,
                'email_verified_at' => $faker->randomElement([now(), null]),
            ]);
        }
    }
}
