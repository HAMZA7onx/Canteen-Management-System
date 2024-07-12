<?php

namespace App\Http\Controllers;

use App\Models\PosDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosDeviceController extends Controller
{
    public function index()
    {
        $posDevices = PosDevice::all();
        return response()->json($posDevices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:pos_devices,ip_address',
            'status' => 'required|in:allowed,unauthorized',
        ]);

        $posDevice = PosDevice::create([
            'ip_address' => $request->ip_address,
            'status' => $request->status,
            'creator' => Auth::user()->email,
            'editors' => [],
        ]);

        return response()->json($posDevice, 201);
    }

    public function show($id)
    {
        $posDevice = PosDevice::findOrFail($id);
        return response()->json($posDevice);
    }

    public function update(Request $request, $id)
    {
        $posDevice = PosDevice::findOrFail($id);

        $request->validate([
            'ip_address' => 'required|ip|unique:pos_devices,ip_address,' . $id,
            'status' => 'required|in:allowed,unauthorized',
        ]);

        $posDevice->ip_address = $request->ip_address;
        $posDevice->status = $request->status;

        // Append the current user's email to editors if not already present
        $editors = $posDevice->editors;
        $currentUserEmail = Auth::user()->email;
        if (!in_array($currentUserEmail, $editors)) {
            $editors[] = $currentUserEmail;
            $posDevice->editors = $editors;
        }

        $posDevice->save();

        return response()->json($posDevice);
    }

    public function destroy($id)
    {
        $posDevice = PosDevice::findOrFail($id);
        $posDevice->delete();

        return response()->json(null, 204);
    }
}
