<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PosDevice;
use Illuminate\Support\Facades\Log;

class CheckAllowedIp
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        Log::info("Detected client IP: " . $clientIp);

        $allowedDevice = PosDevice::where('ip_address', $clientIp)
            ->where('status', 'allowed')
            ->first();

        Log::info("Allowed device found: " . ($allowedDevice ? 'Yes' : 'No'));

        if (!$allowedDevice) {
            Log::warning("Unauthorized access attempt from IP: " . $clientIp);
            return response()->json(['error' => 'Unauthorized IP address'], 403);
        }

        return $next($request);
    }
}
