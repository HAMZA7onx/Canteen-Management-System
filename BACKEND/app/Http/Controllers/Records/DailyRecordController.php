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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function store(Request $request, $day)
    {
        DB::enableQueryLog();

        $model = $this->getModel($day);
        $pivotTable = $day . '_daily_meal';
        $recordTable = $day . '_records';

        $validatedData = $request->validate([
            'badge_id' => 'required|exists:badges,id',
        ]);

        $currentTime = Carbon::now();
        $currentTimeString = $currentTime->format('H:i:s');

        \Log::info("Processing request for day: $day, time: $currentTimeString");

        // Log all meals for the day
        $allMeals = DB::table($pivotTable)->get();
        \Log::info("All meals for $day: " . json_encode($allMeals));

        // Check column information using Schema facade instead of raw SQL
        $columns = Schema::getColumnListing($pivotTable);
        $columnInfo = [];
        foreach ($columns as $column) {
            $type = Schema::getColumnType($pivotTable, $column);
            $columnInfo[$column] = $type;
        }
        \Log::info("Column info for $pivotTable: " . json_encode($columnInfo));

        // Query for the meal
        $meal = DB::table($pivotTable)
            ->whereTime('start_time', '<=', $currentTimeString)
            ->whereTime('end_time', '>', $currentTimeString)
            ->first();

        // Log the query details
        \Log::info("SQL Query: " . DB::getQueryLog()[0]['query']);
        \Log::info("Query Bindings: " . json_encode(DB::getQueryLog()[0]['bindings']));
        \Log::info("Meal found: " . json_encode($meal));

        if (!$meal) {
            // If no meal found, try a more lenient search
            $meal = DB::table($pivotTable)
                ->where(function($query) use ($currentTime) {
                    $query->where(function($q) use ($currentTime) {
                        $q->whereTime('start_time', '<=', $currentTime)
                            ->whereTime('end_time', '>', $currentTime);
                    })
                        ->orWhere(function($q) use ($currentTime) {
                            $q->whereTime('start_time', '>', $currentTime)
                                ->whereTime('end_time', '<', $currentTime);
                        });
                })
                ->first();

            \Log::info("Lenient search SQL: " . DB::getQueryLog()[1]['query']);
            \Log::info("Lenient search bindings: " . json_encode(DB::getQueryLog()[1]['bindings']));
            \Log::info("Lenient search result: " . json_encode($meal));

            if (!$meal) {
                \Log::info("No meal found for day: $day, time: $currentTimeString");
                return response()->json(['error' => "There is no meal available at this time ($currentTimeString) for $day."], 400);
            }
        }

        // Check for existing record
        $existingRecord = DB::table($recordTable)
            ->where($day . '_daily_meal_id', $meal->id)
            ->where('badge_id', $validatedData['badge_id'])
            ->whereDate('created_at', $currentTime->toDateString())
            ->first();

        if ($existingRecord) {
            \Log::info("Existing record found for badge {$validatedData['badge_id']} and meal {$meal->id}");
            return response()->json(['error' => 'A record for this badge and meal already exists today.'], 400);
        }

        // Create the record
        DB::beginTransaction();
        try {
            $record = DB::table($recordTable)->insert([
                $day . '_daily_meal_id' => $meal->id,
                'badge_id' => $validatedData['badge_id'],
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
            DB::commit();
            \Log::info("Record created successfully for day: $day, badge: {$validatedData['badge_id']}, meal: {$meal->id}");
            return response()->json(['message' => 'Record created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to create record: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create record', 'message' => $e->getMessage()], 500);
        }
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
}
