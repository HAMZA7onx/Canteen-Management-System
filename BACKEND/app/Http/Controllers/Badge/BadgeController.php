<?php
namespace App\Http\Controllers\Badge;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RfidImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::with('user')->orderBy('updated_at', 'desc')->get();
        return response()->json($badges);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid' => 'required|string|max:255|unique:badges',
            'status' => 'required|string|in:available,lost,assigned',
        ]);

        $currentUser = Auth::user();

        $badge = Badge::create([
            'user_id' => $validatedData['user_id'],
            'rfid' => $validatedData['rfid'],
            'status' => $validatedData['status'],
            'creator' => $currentUser->email,
            'editors' => json_encode([]),
        ]);

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
            'status' => 'required|string|in:available,lost,assigned',
        ]);

        $currentUser = Auth::user();

        $badge->user_id = $validatedData['user_id'];
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

    public function importRfids(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        try {
            Excel::import(new RfidImport, $file);
            return response()->json(['message' => 'RFIDs imported successfully']);
        } catch (\Exception $e) {
            \Log::error('Error importing RFIDs: ' . $e->getMessage());
            return response()->json(['error' => 'Error importing RFIDs'], 500);
        }
    }

    public function getUsersWithAllRfidsLost()
    {
        $usersWithAllLostRfids = User::select('users.*')
            ->join('badges', 'users.id', '=', 'badges.user_id')
            ->groupBy('users.id')
            ->havingRaw('COUNT(CASE WHEN badges.status != ? THEN 1 END) = 0', ['lost'])
            ->havingRaw('COUNT(badges.id) > 0')
            ->get();

        return response()->json($usersWithAllLostRfids);
    }

    public function getUsersWithoutRfids()
    {
        $users =  User::whereDoesntHave('badge')->get();
        return response()->json($users);
    }

    public function updateBadgeStatus(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);
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
        $badge = Badge::findOrFail($id);
        $user = User::findOrFail($request->input('userId'));

        if ($badge->status !== 'available') {
            return response()->json(['error' => 'This badge is not available for assignment'], 400);
        }

        $badge->user_id = $user->id;
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
