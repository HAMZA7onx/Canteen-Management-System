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
            'name' => 'required|unique:pos_devices,name',
            'ip_address' => 'required|ip|unique:pos_devices,ip_address',
            'status' => 'required|in:allowed,unauthorized',
        ]);

        try {
            $posDevice = PosDevice::create([
                'name' => $request->name,
                'ip_address' => $request->ip_address,
                'status' => $request->status,
                'creator' => Auth::user()->email,
                'editors' => [],
            ]);
            return response()->json($posDevice, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A POS device with this name or IP address already exists.'], 422);
            }
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        $posDevice = PosDevice::findOrFail($id);

        $request->validate([
            'name' => 'unique:pos_devices,name,' . $id,
            'ip_address' => 'ip|unique:pos_devices,ip_address,' . $id,
            'status' => 'in:allowed,unauthorized',
        ]);

        try {
            if ($request->has('name')) {
                $posDevice->name = $request->name;
            }
            if ($request->has('ip_address')) {
                $posDevice->ip_address = $request->ip_address;
            }
            if ($request->has('status')) {
                $posDevice->status = $request->status;
            }

            // Append the current user's email to editors if not already present
            $editors = $posDevice->editors;
            $currentUserEmail = Auth::user()->email;
            if (!in_array($currentUserEmail, $editors)) {
                $editors[] = $currentUserEmail;
                $posDevice->editors = $editors;
            }

            $posDevice->save();
            return response()->json($posDevice);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A POS device with this name or IP address already exists.'], 422);
            }
            throw $e;
        }
    }

    public function show($id)
    {
        $posDevice = PosDevice::findOrFail($id);
        return response()->json($posDevice);
    }

    public function destroy($id)
    {
        $posDevice = PosDevice::findOrFail($id);
        $posDevice->delete();

        return response()->json(null, 204);
    }
}
