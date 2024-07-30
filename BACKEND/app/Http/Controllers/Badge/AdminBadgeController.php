<?php

namespace App\Http\Controllers\Badge;
use App\Http\Controllers\Controller;
use App\Models\AdminBadge;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\adminRfidImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class AdminBadgeController extends Controller
{
    public function index()
    {
        $badges = AdminBadge::with('admin')->orderBy('updated_at', 'desc')->get();
        return response()->json($badges);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'rfid' => 'required|string|max:255|unique:admins_badges',
            'status' => 'required|string|in:available,lost,assigned',
        ]);

        $currentUser = Auth::user();

        $badge = AdminBadge::create([
            'admin_id' => $validatedData['admin_id'],
            'rfid' => $validatedData['rfid'],
            'status' => $validatedData['status'],
            'creator' => $currentUser->email,
            'editors' => json_encode([]),
        ]);

        $badge->setActiveRfid();
        $badge->load('admin');

        return response()->json($badge, 201);
    }

    public function update(Request $request, $id)
    {
        $badge = AdminBadge::findOrFail($id);

        $validatedData = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'rfid' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins_badges')->ignore($badge->id),
            ],
            'status' => 'required|string|in:available,lost,assigned',
        ]);

        $currentUser = Auth::user();

        $badge->admin_id = $validatedData['admin_id'];
        $badge->rfid = $validatedData['rfid'];
        $badge->status = $validatedData['status'];

        // Update editors
        $editors = json_decode($badge->editors, true) ?: [];
        if (!in_array($currentUser->email, $editors)) {
            $editors[] = $currentUser->email;
            $badge->editors = json_encode($editors);
        }

        $badge->save();

        if ($validatedData['status'] === 'active') {
            $badge->setActiveRfid();
        }

        $badge->load('admin');

        return response()->json($badge);
    }
    public function show($id)
    {
        $badge = AdminBadge::with('admin')->findOrFail($id);
        return response()->json($badge);
    }

    public function destroy($id)
    {
        $badge = AdminBadge::findOrFail($id);
        $badge->delete();
        return response()->json(null, 204);
    }

    public function importRfids(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        try {
            $import = new adminRfidImport();
            Excel::import($import, $file);
            $result = $import->getResult();
            return response()->json([
                'message' => 'RFIDs imported successfully',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            \Log::error('Error importing RFIDs: ' . $e->getMessage());
            return response()->json(['error' => 'Error importing RFIDs'], 500);
        }
    }

    public function getUsersWithAllRfidsLost()
    {
        $usersWithAllLostRfids = Admin::select('admins.*')
            ->join('admins_badges', 'admins.id', '=', 'admins_badges.admin_id')
            ->groupBy('admins.id')
            ->havingRaw('COUNT(CASE WHEN admins_badges.status != ? THEN 1 END) = 0', ['lost'])
            ->havingRaw('COUNT(admins_badges.id) > 0')
            ->get();

        return response()->json($usersWithAllLostRfids);
    }

    public function getUsersWithoutRfids()
    {
        $users =  Admin::whereDoesntHave('badge')->get();
        return response()->json($users);
    }

    public function updateBadgeStatus(Request $request, $id)
    {
        $badge = AdminBadge::findOrFail($id);
        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['available', 'assigned', 'lost'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $badge->status = $newStatus;

        // Decode the editors JSON string to an array
        $editors = json_decode($badge->editors, true) ?? [];

        // Add the current user's email to the editors array if it's not already there
        if (!in_array(auth()->user()->email, $editors)) {
            $editors[] = auth()->user()->email;
        }

        // Encode the editors array back to JSON
        $badge->editors = json_encode($editors);

        $badge->save();

        return response()->json($badge);
    }

    public function assignRfidToUser(Request $request, $id)
    {
        $badge = AdminBadge::findOrFail($id);
        $user = Admin::findOrFail($request->input('userId'));

        if ($badge->status !== 'available') {
            return response()->json(['error' => 'This badge is not available for assignment'], 400);
        }

        $badge->admin_id = $user->id;
        $badge->status = 'assigned';

        // Decode the editors JSON string to an array
        $editors = json_decode($badge->editors, true) ?? [];

        // Add the current user's email to the editors array if it's not already there
        if (!in_array(auth()->user()->email, $editors)) {
            $editors[] = auth()->user()->email;
        }

        // Encode the editors array back to JSON
        $badge->editors = json_encode($editors);

        $badge->save();

        return response()->json($badge);
    }
}
