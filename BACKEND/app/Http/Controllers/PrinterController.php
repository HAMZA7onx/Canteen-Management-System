<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\PosDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrinterController extends Controller
{
    public function index()
    {
        $printers = Printer::orderBy('updated_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $printers
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:printers',
            'ip_address' => 'required|ip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $printer = new Printer($validator->validated());
        $printer->creator = $request->user()->email;
        $printer->editors = [];
        $printer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Printer created successfully',
            'data' => $printer
        ], 201);
    }

    public function show($id)
    {
        $printer = Printer::find($id);

        if (!$printer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Printer not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $printer
        ]);
    }

    public function update(Request $request, $id)
    {
        $printer = Printer::find($id);

        if (!$printer) {
            return response()->json([
                'status' => 'error',
                    'message' => 'Printer not found'
                ], 404);
            }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:printers,name,' . $id,
            'ip_address' => 'sometimes|required|ip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $printer->update($validator->validated());

        // Add current user's email to editors if not already present
        $editors = $printer->editors;
        $userEmail = $request->user()->email;
        if (!in_array($userEmail, $editors)) {
            $editors[] = $userEmail;
            $printer->editors = $editors;
            $printer->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Printer updated successfully',
            'data' => $printer
        ]);
    }

    public function destroy($id)
    {
        $printer = Printer::find($id);

        if (!$printer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Printer not found'
            ], 404);
        }

        $printer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Printer deleted successfully'
        ]);
    }

    public function getAssignablePosDevices($id)
    {
        $assignedDevices = PosDevice::whereNotNull('printer_id')->pluck('id');
        $availableDevices = PosDevice::whereNull('printer_id')
            ->orWhere('printer_id', $id)
            ->whereNotIn('id', $assignedDevices)
            ->get();
        $printerDevices = PosDevice::where('printer_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'available_devices' => $availableDevices,
            'printer_devices' => $printerDevices
        ]);
    }

    public function assignPosDevices(Request $request, $id)
    {
        $printer = Printer::findOrFail($id);
        $deviceIds = $request->input('device_ids', []);

        // Verify that all devices exist and are available
        $devices = PosDevice::whereIn('id', $deviceIds)
            ->where(function ($query) use ($id) {
                $query->whereNull('printer_id')
                    ->orWhere('printer_id', $id);
            })
            ->get();

        if ($devices->count() !== count($deviceIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'One or more selected devices are not available for assignment'
            ], 400);
        }

        // Assign devices to the printer
        $devices->each(function ($device) use ($printer) {
            $device->printer()->associate($printer);
            $device->save();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'POS devices assigned successfully',
            'assigned_count' => $devices->count()
        ]);
    }

    public function unassignPosDevice(Request $request, $id)
    {
        $deviceId = $request->input('device_id');
        PosDevice::where('id', $deviceId)->update(['printer_id' => null]);

        return response()->json([
            'status' => 'success',
            'message' => 'POS device unassigned successfully'
        ]);
    }
}
