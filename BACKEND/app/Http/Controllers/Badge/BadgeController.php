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

    public function importRfids(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        try {
            $rfids = Excel::toArray(new RfidImport, $file)[0];
            \Log::info('Imported RFIDs:', $rfids);
        } catch (\Exception $e) {
            \Log::error('Error reading RFIDs from file: ' . $e->getMessage());
            return response()->json(['error' => 'Error reading RFIDs from file'], 500);
        }

        $usersWithoutBadge = User::whereDoesntHave('badge')->get()->pluck('id');
        $existingRfids = Badge::pluck('rfid')->toArray();

        foreach ($rfids as $rfidData) {
            try {
                $rfid = $rfidData['rfid'];

                // Check if the RFID already exists in the badges table
                if (in_array($rfid, $existingRfids)) {
                    \Log::info("RFID $rfid already exists, skipping.");
                    continue;
                }

                if ($usersWithoutBadge->isNotEmpty()) {
                    $userId = $usersWithoutBadge->shift();
                    $context = [
                        'userId' => $userId,
                        'rfid' => $rfid,
                    ];
                    \Log::info('Creating badge with user ID and RFID', $context);
                    $badge = Badge::create([
                        'user_id' => $userId,
                        'rfid' => $rfid,
                        'status' => 'assigned',
                    ]);
                    \Log::info('Created badge:', $badge->toArray());
                } else {
                    $context = [
                        'rfid' => $rfid,
                    ];
                    \Log::info('Creating badge with RFID', $context);
                    $badge = Badge::create([
                        'rfid' => $rfid,
                        'status' => 'available',
                    ]);
                    \Log::info('Created badge:', $badge->toArray());
                }
            } catch (\Exception $e) {
                \Log::error('Error creating badge: ' . $e->getMessage());
                return response()->json(['error' => 'Error creating badges'], 500);
            }
        }

        return response()->json(['message' => 'RFIDs imported successfully']);
    }

    public function getUsersWithAllRfidsLost()
    {
        $users = User::whereHas('badges', function ($query) {
            $query->where('status', 'lost');
        })
            ->with('badges')
            ->get();

        $usersWithAllRfidsLost = $users->filter(function ($user) {
            return $user->badges->every(function ($badge) {
                return $badge->status === 'lost';
            });
        });

        return response()->json($usersWithAllRfidsLost);
    }

    public function getUsersWithoutRfids()
    {
        $users = User::doesntHave('badges')->get();
        return response()->json($users);
    }

    public function updateBadgeStatus(Request $request, $badgeId)
    {
        try {
            $badge = Badge::findOrFail($badgeId);

            $validatedData = $request->validate([
                'status' => 'required|in:available,assigned,lost',
            ]);

            $badge->status = $validatedData['status'];
            $badge->save();

            return response()->json($badge);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating badge status: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'An error occurred while updating the badge status.'], 500);
        }
    }


    public function assignRfidToUser(Request $request, $badgeId)
    {
        $badge = Badge::findOrFail($badgeId);

        $validatedData = $request->validate([
            'userId' => 'required|exists:users,id',
        ]);

        $userId = $validatedData['userId'];

        $badge->user_id = $userId;
        $badge->status = 'assigned';
        $badge->save();

        return response()->json($badge);
    }

}
