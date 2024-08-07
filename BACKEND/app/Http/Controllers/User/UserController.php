<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    function index()
    {
        $users = User::with('category')->orderBy('updated_at', 'desc')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:user_category,id',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentUser = Auth::user();
        $category = UserCategory::findOrFail($request->category_id);
        $affectedCategories = [$category->name];

        // Generate matriculation number
        $year = date('Y');
        $categoryCode = strtoupper(substr($category->name, 0, 3));
        $lastUser = User::where('category_id', $request->category_id)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        $sequentialNumber = $lastUser ? (intval(substr($lastUser->matriculation_number, -4)) + 1) : 1;
        $matriculationNumber = $year . $categoryCode . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

        $user = User::create([
            'category_id' => $request->category_id,
            'affected_categories' => json_encode($affectedCategories),
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'status' => 'assigned',
            'api_token' => str_replace('-', '', substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 20)),
            'creator' => $currentUser->email,
            'editors' => json_encode([]),
            'matriculation_number' => $matriculationNumber,
        ]);

        $user->load('category');
        $status = "success";
        $response = ['user' => $user, 'status' => $status];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'exists:user_category,id',
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($request->has('category_id')) {
            $user->category_id = $request->category_id;

            // Update affected_categories with the new category name
            $category = UserCategory::findOrFail($request->category_id);
            $affectedCategories = json_decode($user->affected_categories, true) ?: [];
            $affectedCategories[] = $category->name;
            $user->affected_categories = json_encode(array_unique($affectedCategories));
        }

        $user->name = $request->has('name') ? $request->name : $user->name;
        $user->email = $request->has('email') ? $request->email : $user->email;
        $user->phone_number = $request->has('phone_number') ? $request->phone_number : $user->phone_number;
        $user->gender = $request->has('gender') ? $request->gender : $user->gender;

        // Update editors
        $editors = json_decode($user->editors, true) ?: [];
        if (!in_array($currentUser->email, $editors)) {
            $editors[] = $currentUser->email;
            $user->editors = json_encode($editors);
        }

        $user->save();

        $user->load('category');

        $status = "success";
        $response = ['user' => $user, 'status' => $status];
        return response()->json($response);
    }

    function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getUserDetails($id)
    {
        $user = User::where('id', $id)->get()->first();
        return response()->json(['user' => $user], 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'category_id' => 'required|exists:user_category,id',
        ]);

        $file = $request->file('file');
        $categoryId = $request->input('category_id');

        try {
            $import = new UsersImport($categoryId);
            Excel::import($import, $file);

            $skippedCount = count($import->skippedRows);
            $importedCount = $import->importedCount;
            $updatedCount = $import->updatedCount;

            $message = "$importedCount users were imported successfully.";
            if ($updatedCount > 0) {
                $message .= " $updatedCount previously deleted users were restored.";
            }
            if ($skippedCount > 0) {
                $message .= " $skippedCount users were skipped due to existing emails.";
            }

            return response()->json([
                'message' => $message,
                'skipped_rows' => $import->skippedRows,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error importing users: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while importing users.'], 500);
        }
    }

}
