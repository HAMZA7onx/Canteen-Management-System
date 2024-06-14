<?php

namespace App\Http\Controllers\Badge;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::with('user')->get();
        return response()->json($badges);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid' => 'required|string|max:255|unique:badges',
            'status' => 'required|string|in:active,inactive',
        ]);

        $badge = Badge::create($validatedData);
        $badge->setActiveRfid();
        $badge->load('user');

        return response()->json($badge, 201);
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid' => [
                'required',
                'string',
                'max:255',
                Rule::unique('badges')->ignore($badge->id),
            ],
            'status' => 'required|string|in:active,inactive',
        ]);

        $badge->update($validatedData);

        if ($validatedData['status'] === 'active') {
            $badge->setActiveRfid();
        }
        $badge->load('user');

        return response()->json($badge);
    }
    public function show($id)
    {
        $badge = Badge::with('user')->findOrFail($id);
        return response()->json($badge);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return response()->json(null, 204);
    }
}
