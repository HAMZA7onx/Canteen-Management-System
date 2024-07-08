<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeekSchedule;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Seeder6_RecordsDashboard extends Seeder
{
    public function run()
    {
        $startDate = Carbon::create(2023, 1, 1);
        $endDate = Carbon::now();

        $weekSchedules = WeekSchedule::all();
        $badges = Badge::all();

        $dayRelations = [
            'monday' => 'mondayDailyMeals',
            'tuesday' => 'tuesdayDailyMeals',
            'wednesday' => 'wednesdayDailyMeals',
            'thursday' => 'thursdayDailyMeals',
            'friday' => 'fridayDailyMeals',
            'saturday' => 'saturdayDailyMeals',
            'sunday' => 'sundayDailyMeals',
        ];

        $recordModels = [
            'monday' => \App\Models\Records\MondayRecord::class,
            'tuesday' => \App\Models\Records\TuesdayRecord::class,
            'wednesday' => \App\Models\Records\WednesdayRecord::class,
            'thursday' => \App\Models\Records\ThursdayRecord::class,
            'friday' => \App\Models\Records\FridayRecord::class,
            'saturday' => \App\Models\Records\SaturdayRecord::class,
            'sunday' => \App\Models\Records\SundayRecord::class,
        ];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dayOfWeek = strtolower($currentDate->format('l'));
            $recordModel = $recordModels[$dayOfWeek];
            $relationName = $dayRelations[$dayOfWeek];

            foreach ($weekSchedules as $weekSchedule) {
                $meals = $weekSchedule->$relationName;

                // Skip if no meals are assigned for this day
                if ($meals->isEmpty()) {
                    $this->command->info("No meals found for {$dayOfWeek} in week schedule {$weekSchedule->id}");
                    continue;
                }

                foreach ($meals as $meal) {
                    $pivotData = $meal->pivot;

                    // Skip if pivot data is missing or id is null
                    if (!$pivotData || !$pivotData->id) {
                        $this->command->info("Invalid pivot data for {$dayOfWeek} meal in week schedule {$weekSchedule->id}");
                        continue;
                    }

                    // Randomly select some badges (30% chance for each badge)
                    $selectedBadges = $badges->filter(function ($badge) {
                        return rand(1, 100) <= 30;
                    });

                    foreach ($selectedBadges as $badge) {
                        try {
                            DB::beginTransaction();

                            $recordModel::create([
                                $dayOfWeek . '_daily_meal_id' => $pivotData->id,
                                'badge_id' => $badge->id,
                                'created_at' => $currentDate->copy()->setTimeFrom(Carbon::parse($pivotData->start_time)),
                                'updated_at' => $currentDate->copy()->setTimeFrom(Carbon::parse($pivotData->start_time)),
                            ]);

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $this->command->error("Error creating record for {$dayOfWeek}, meal ID {$pivotData->id}, badge ID {$badge->id}: " . $e->getMessage());
                        }
                    }
                }
            }

            $currentDate->addDay();
        }

        $this->command->info('Records dashboard data seeded successfully.');
    }
}
