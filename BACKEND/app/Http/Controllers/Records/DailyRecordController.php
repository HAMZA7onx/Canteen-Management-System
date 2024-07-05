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
        $model = $this->getModel($day);
        $record = $model::create($request->all());
        return response()->json($record, 201);
    }

    public function show($day, $id)
    {
        $model = $this->getModel($day);
        $record = $model::with(['badge', $day . 'DailyMeal'])->findOrFail($id);
        return response()->json($record);
    }

    public function destroy($day, $id)
    {
        $model = $this->getModel($day);
        $record = $model::findOrFail($id);
        $record->delete();
        return response()->json(null, 204);
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
