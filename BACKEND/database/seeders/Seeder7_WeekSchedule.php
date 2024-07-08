<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeekSchedule;
use App\Models\DailyMeal;
use App\Models\UserCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Seeder7_WeekSchedule extends Seeder
{
    public function run()
    {
        // Get or create a user to be the creator
        $creator = User::first() ?? User::factory()->create();

        // Create some WeekSchedule entries
        $weekSchedules = [
            [
                'mode_name' => 'Regular Week',
                'description' => 'Standard weekly schedule',
                'status' => 'active',
                'creator' => $creator->email,
                'editors' => json_encode([$creator->email])
            ],
            [
                'mode_name' => 'Holiday Week',
                'description' => 'Schedule for holiday weeks',
                'status' => 'inactive',
                'creator' => $creator->email,
                'editors' => json_encode([$creator->email])
            ],
        ];

        foreach ($weekSchedules as $schedule) {
            WeekSchedule::create($schedule);
        }

        // Create some DailyMeal entries if they don't exist
        $mealNames = ['Breakfast', 'Lunch', 'Dinner'];
        foreach ($mealNames as $name) {
            DailyMeal::firstOrCreate(['name' => $name]);
        }

        // Get all WeekSchedules, DailyMeals, and UserCategories
        $allWeekSchedules = WeekSchedule::all();
        $allDailyMeals = DailyMeal::all();
        $userCategories = UserCategory::all();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($allWeekSchedules as $weekSchedule) {
            foreach ($days as $day) {
                $startTime = Carbon::create(2023, 1, 1, 7, 0, 0); // Start at 7:00 AM

                foreach ($allDailyMeals as $meal) {
                    $endTime = $startTime->copy()->addHours(2);

                    DB::transaction(function () use ($weekSchedule, $day, $meal, $startTime, $endTime, $userCategories) {
                        $pivotData = [
                            'start_time' => $startTime->format('H:i'),
                            'end_time' => $endTime->format('H:i'),
                            'price' => rand(500, 2000) / 100, // Random price between $5 and $20
                        ];

                        $weekSchedule->{"{$day}DailyMeals"}()->attach($meal->id, $pivotData);

                        $pivotId = DB::table("{$day}_daily_meal")
                            ->where('week_schedule_id', $weekSchedule->id)
                            ->where('daily_meal_id', $meal->id)
                            ->value('id');

                        foreach ($userCategories as $category) {
                            DB::table("{$day}_discounts")->insert([
                                'meal_id' => $pivotId,
                                'category_id' => $category->id,
                                'discount' => rand(0, 50), // Random discount between 0% and 50%
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    });

                    $startTime->addHours(5); // Move to the next meal time
                }
            }
        }
    }
}
