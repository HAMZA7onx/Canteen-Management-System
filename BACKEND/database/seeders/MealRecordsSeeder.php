<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MealRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming there are records in the badges and meal_schedules tables
        $badgeIds = DB::table('badges')->pluck('id');
        $mealScheduleIds = DB::table('meal_schedules')->pluck('id');

        if ($badgeIds->isEmpty() || $mealScheduleIds->isEmpty()) {
            // Ensure you have data in the badges and meal_schedules tables before running this seeder
            $this->command->info('Badges or Meal Schedules table is empty, please seed these tables first.');
            return;
        }

        $mealRecords = [];

        for ($i = 0; $i < 10; $i++) {
            $mealRecords[] = [
                'badge_id' => $badgeIds->random(),
                'meal_schedule_id' => $mealScheduleIds->random(),
                'taken_at' => Carbon::now()->subDays(rand(0, 30)),
            ];
        }

        DB::table('meal_records')->insert($mealRecords);
    }
}
