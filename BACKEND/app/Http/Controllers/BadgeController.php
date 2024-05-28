<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::with('user', 'mealRecords')->get();
        return response()->json($badges);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid' => 'required|string|unique:badges',
            'status' => 'required|in:active,inactive',
        ]);

        $badge = Badge::create($validatedData);

        return response()->json($badge, 201);
    }

    public function show(Badge $badge)
    {
        $badge->load('user', 'mealRecords');
        return response()->json($badge);
    }

    public function update(Request $request, Badge $badge)
    {
        $validatedData = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'rfid' => 'sometimes|string|unique:badges,rfid,' . $badge->id,
            'status' => 'sometimes|in:active,inactive',
        ]);

        $badge->update($validatedData);

        return response()->json($badge);
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return response()->json(['message' => 'Badge deleted successfully']);
    }
}
