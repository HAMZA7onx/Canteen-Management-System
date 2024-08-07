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

    public function getStatus(Request $request)
    {
        $ip_address = $request->ip();

        \Log::info("getStatus method called", [
            'ip_address' => $ip_address,
            'user_agent' => $request->userAgent()
        ]);

        $posDevice = PosDevice::where('ip_address', $ip_address)->get();

        \Log::info("POS device status retrieved", [
            'ip_address' => $ip_address,
            'device_count' => $posDevice->count()
        ]);

        return response()->json($posDevice);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:pos_devices,name',
            'ip_address' => 'required|ip|unique:pos_devices,ip_address',
            'status' => 'required|in:allowed,unauthorized',
            'print_statistics' => 'required|in:active,inactive',
            'print_tickets' => 'required|in:active,inactive',
        ]);

        try {
            $posDevice = PosDevice::create([
                'name' => $request->name,
                'ip_address' => $request->ip_address,
                'status' => $request->status,
                'print_statistics' => $request->print_statistics,
                'print_tickets' => $request->print_tickets,
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
            'print_statistics' => 'in:active,inactive',
            'print_tickets' => 'in:active,inactive',
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
            if ($request->has('print_statistics')) {
                $posDevice->print_statistics = $request->print_statistics;
            }
            if ($request->has('print_tickets')) {
                $posDevice->print_tickets = $request->print_tickets;
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
