<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReportSubscription;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminReportSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = AdminReportSubscription::with('admin')->get();
        return response()->json($subscriptions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'is_active' => 'boolean',
        ]);

        $subscription = AdminReportSubscription::create($validated);
        return response()->json($subscription, 201);
    }

    public function update(Request $request, AdminReportSubscription $subscription)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'is_active' => 'boolean',
        ]);

        $subscription->update($validated);
        return response()->json($subscription);
    }

    public function destroy(AdminReportSubscription $subscription)
    {
        $subscription->delete();
        return response()->json(null, 204);
    }

    public function getAdmins()
    {
        $admins = Admin::all(['id', 'name', 'email']);
        return response()->json($admins);
    }
}
