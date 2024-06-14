<?php

namespace App\Http\Controllers\Badge;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::with('user')->get();
        return response()->json($badges);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->rules());
        $badge = Badge::create($validatedData);
        return response()->json($badge, 201);
    }

    public function show($id)
    {
        $badge = Badge::with('user')->findOrFail($id);
        return response()->json($badge);
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);
        $validatedData = $request->validate($this->rules());
        $badge->update($validatedData);
        $badge->load('user'); // Eager load the associated user data
        return response()->json($badge);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return response()->json(null, 204);
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'rfid' => 'required|string|max:255|unique:badges',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
