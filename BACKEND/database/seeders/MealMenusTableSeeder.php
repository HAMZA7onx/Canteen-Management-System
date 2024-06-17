<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealMenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meal_menus')->insert([
            [
                'menu_name' => 'Breakfast Menu',
                'description' => 'Start your day with our delicious breakfast options.',
                'price' => 9.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_name' => 'Lunch Menu',
                'description' => 'Enjoy our fresh and flavorful lunch dishes.',
                'price' => 12.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_name' => 'Dinner Menu',
                'description' => 'Indulge in our exquisite dinner selections.',
                'price' => 19.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
