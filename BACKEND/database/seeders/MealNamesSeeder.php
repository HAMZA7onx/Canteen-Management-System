<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mealNames = [
            [
                'name' => 'Breakfast',
                'description' => 'The first meal of the day, typically consumed in the morning.',
            ],
            [
                'name' => 'Brunch',
                'description' => 'A meal combining breakfast and lunch, usually served mid-morning or early afternoon.',
            ],
            [
                'name' => 'Lunch',
                'description' => 'The midday meal, typically consumed around noon or early afternoon.',
            ],
            [
                'name' => 'Dinner',
                'description' => 'The main meal of the day, typically consumed in the evening.',
            ],
            [
                'name' => 'Snack',
                'description' => 'A small portion of food eaten between meals.',
            ],
            [
                'name' => 'Dessert',
                'description' => 'A sweet course or dish served at the end of a meal.',
            ],
        ];

        DB::table('meal_names')->insert($mealNames);
    }
}
