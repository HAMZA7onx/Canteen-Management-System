<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Records\MondayRecord;
use App\Models\Records\TuesdayRecord;
use App\Models\Records\WednesdayRecord;
use App\Models\Records\ThursdayRecord;
use App\Models\Records\FridayRecord;
use App\Models\Records\SaturdayRecord;
use App\Models\Records\SundayRecord;
use App\Models\WeekSchedule;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyRecordController extends Controller
{
    private $models = [
        'monday' => MondayRecord::class,
        'tuesday' => TuesdayRecord::class,
        'wednesday' => WednesdayRecord::class,
        'thursday' => ThursdayRecord::class,
        'friday' => FridayRecord::class,
        'saturday' => SaturdayRecord::class,
        'sunday' => SundayRecord::class,
    ];

    public function index($day)
    {
        $model = $this->getModel($day);
        $records = $model::with(['badge', $day . 'DailyMeal'])->get();
        return response()->json($records);
    }

    public function show($day, $id)
    {
        $model = $this->getModel($day);
        $record = $model::with(['badge', $day . 'DailyMeal'])->findOrFail($id);
        return response()->json($record);
    }

    private function getModel($day)
    {
        $day = strtolower($day);
        if (!isset($this->models[$day])) {
            abort(404, 'Invalid day');
        }
        return $this->models[$day];
    }

    public function verifyBadge($rfid)
    {
        $badge = Badge::where('rfid', $rfid)->first();
        return response()->json([
            'exists' => $badge !== null,
            'message' => $badge ? 'Badge found' : 'Badge not found',
            'badge_id' => $badge ? $badge->id : null,
        ]);
    }


    public function store(Request $request, $day)
    {
        DB::enableQueryLog();
        $pivotTable = $day . '_daily_meal';
        $recordTable = $day . '_records';

        $validatedData = $request->validate([
            'badge_id' => 'required|exists:badges,id',
        ]);

        $currentTime = Carbon::now();
        $currentTimeString = $currentTime->format('H:i:s');

        \Log::info("Processing request for day: $day, time: $currentTimeString");

        // Find the active week schedule
        $activeSchedule = WeekSchedule::where('status', 'active')->first();
        if (!$activeSchedule) {
            \Log::error("No active week schedule found");
            return response()->json(['error' => 'No active week schedule found'], 400);
        }

        \Log::info("Active week schedule: " . json_encode($activeSchedule));

        // Find the current meal for the given day in the active schedule
        $currentMeal = DB::table($pivotTable)
            ->join('daily_meals', 'daily_meals.id', '=', $pivotTable . '.daily_meal_id')
            ->where($pivotTable . '.week_schedule_id', $activeSchedule->id)
            ->whereRaw("?::time BETWEEN {$pivotTable}.start_time AND {$pivotTable}.end_time", [$currentTimeString])
            ->select($pivotTable . '.id as pivot_id', 'daily_meals.id as meal_id', $pivotTable . '.*', 'daily_meals.*')
            ->first();

        if (!$currentMeal) {
            \Log::info("No active meal found for day: $day, time: $currentTimeString in active schedule");
            return response()->json(['error' => "There is no meal available at this time in the active schedule."], 400);
        }

        \Log::info("Current meal found: " . json_encode($currentMeal));

        // Verify that the meal exists in the pivot table
        $mealExists = DB::table($pivotTable)->where('id', $currentMeal->pivot_id)->exists();
        if (!$mealExists) {
            \Log::error("Meal with pivot_id {$currentMeal->pivot_id} does not exist in {$pivotTable}");
            return response()->json(['error' => 'Invalid meal id'], 400);
        }

        // Check for existing record for this badge and meal (regardless of date)
        $existingRecord = DB::table($recordTable)
            ->where($day . '_daily_meal_id', $currentMeal->pivot_id)
            ->where('badge_id', $validatedData['badge_id'])
            ->first();

        if ($existingRecord) {
            \Log::info("Existing record found for badge {$validatedData['badge_id']} and meal {$currentMeal->pivot_id}");
            return response()->json(['error' => 'A record for this badge and meal already exists.'], 400);
        }

        // Create the record
        DB::beginTransaction();
        try {
            $record = DB::table($recordTable)->insert([
                $day . '_daily_meal_id' => $currentMeal->pivot_id,
                'badge_id' => $validatedData['badge_id'],
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
            DB::commit();
            \Log::info("Record created successfully for day: $day, badge: {$validatedData['badge_id']}, meal: {$currentMeal->pivot_id} in active schedule");
            return response()->json(['message' => 'Record created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to create record: " . $e->getMessage());
            \Log::error("SQL: " . last(DB::getQueryLog())['query']);
            return response()->json(['error' => 'Failed to create record', 'message' => $e->getMessage()], 500);
        }
    }

    public function getCurrentMeal()
    {
        $currentDay = strtolower(Carbon::now()->format('l'));
        $currentTime = Carbon::now()->format('H:i:s');

        $activeSchedule = WeekSchedule::where('status', 'active')->first();
        if (!$activeSchedule) {
            return response()->json(['message' => 'No active schedule found'], 404);
        }

        $pivotTable = $currentDay . '_daily_meal';
        $currentMeal = DB::table($pivotTable)
            ->join('daily_meals', 'daily_meals.id', '=', $pivotTable . '.daily_meal_id')
            ->where($pivotTable . '.week_schedule_id', $activeSchedule->id)
            ->whereRaw("?::time BETWEEN {$pivotTable}.start_time AND {$pivotTable}.end_time", [$currentTime])
            ->select('daily_meals.name', $pivotTable . '.start_time', $pivotTable . '.end_time', $pivotTable . '.price')
            ->first();

        if ($currentMeal) {
            return response()->json($currentMeal);
        } else {
            return response()->json(['message' => 'No meal assigned at the current time'], 404);
        }
    }
}
