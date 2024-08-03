<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\FoodComposant;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FoodComposantsController extends Controller
{
    public function index()
    {
        $foodComposants = FoodComposant::orderBy('updated_at', 'desc')->get();
        return response()->json($foodComposants);
    }

    public function store(Request $request)
    {
        \Log::info('Create request:', ['all' => $request->all(), 'files' => $request->allFiles()]);

        $validatedData = $request->validate([
            'name' => 'required|unique:food_composants,name',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,webp,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('food_composant_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            $foodComposant = FoodComposant::create($validatedData);
            return response()->json($foodComposant, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A food composant with this name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function update(Request $request, FoodComposant $foodComposant)
    {
        $content = $request->getContent();
        $boundary = substr($content, 0, strpos($content, "\r\n"));
        $parts = array_slice(explode($boundary, $content), 1, -1);

        $data = [];
        foreach ($parts as $part) {
            if (strpos($part, 'filename') !== false) {
                preg_match('/name="([^"]+)"/', $part, $matches);
                $name = $matches[1];
                preg_match('/filename="([^"]+)"/', $part, $matches);
                $filename = $matches[1];
                $fileContent = substr($part, strpos($part, "\r\n\r\n") + 4, -2);
                $data[$name] = [
                    'name' => $filename,
                    'content' => $fileContent,
                ];
            } else {
                preg_match('/name="([^"]+)"\s*([\s\S]+)?/', $part, $matches);
                $name = $matches[1];
                $value = isset($matches[2]) ? trim($matches[2]) : '';
                $data[$name] = $value;
            }
        }

        \Log::info('Parsed data:', $data);

        $validator = Validator::make($data, [
            'name' => 'required|unique:food_composants,name,' . $foodComposant->id,
            'description' => 'nullable',
            'image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            if (isset($data['image'])) {
                // Delete old image if exists
                if ($foodComposant->image) {
                    Storage::disk('public')->delete($foodComposant->image);
                }

                $image = $data['image'];
                $imagePath = 'food_composant_images/' . $image['name'];
                Storage::disk('public')->put($imagePath, $image['content']);
                $validatedData['image'] = $imagePath;
            }

            $foodComposant->update($validatedData);
            return response()->json($foodComposant);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A food composant with this name already exists.'], 422);
            }
            throw $e;
        }
    }


    public function show(FoodComposant $foodComposant)
    {
        return response()->json($foodComposant);
    }

    public function destroy(FoodComposant $foodComposant)
    {
        $foodComposant->delete();

        return response()->json(null, 204);
    }
}
