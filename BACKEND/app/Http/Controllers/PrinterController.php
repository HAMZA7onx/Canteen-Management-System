<?php

namespace App\Http\Controllers;

use App\Models\Printer;
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
}
