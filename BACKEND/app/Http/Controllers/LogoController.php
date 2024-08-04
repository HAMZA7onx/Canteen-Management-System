<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            // Empty the logos table by deleting all records
            Logo::truncate();

            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/logos', $filename);

            $logo = Logo::create([
                'path' => Storage::url($path),
                'is_active' => true
            ]);

            return response()->json([
                'message' => 'Logo uploaded successfully',
                'path' => $logo->path
            ], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function get()
    {
        $logo = Logo::where('is_active', true)->latest()->first();

        if ($logo) {
            return response()->json(['path' => $logo->path], 200);
        }

        return response()->json(['message' => 'No logo found'], 404);
    }
}
