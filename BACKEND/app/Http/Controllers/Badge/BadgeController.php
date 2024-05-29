<?php

namespace App\Http\Controllers\Badge;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::all();
        return response()->json($badges);
    }

    public function store(Request $request)
    {
        $badge = Badge::create($request->all());
        return response()->json($badge, 201);
    }

    public function show($id)
    {
        $badge = Badge::findOrFail($id);
        return response()->json($badge);
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);
        $badge->update($request->all());
        return response()->json($badge);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return response()->json(null, 204);
    }
}
