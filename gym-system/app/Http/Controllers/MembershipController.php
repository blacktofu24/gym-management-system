<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipPlan;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AuditLog; // To track the purchase

class MembershipController extends Controller
{
    public function index()
    {
        // Get all available plans
        $plans = MembershipPlan::all();
        return view('member.plans.index', compact('plans'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:membership_plans,id'
        ]);

        $user = Auth::user();
        $plan = MembershipPlan::findOrFail($request->plan_id);

        // Prevent buying if they already have an active membership
        if ($user->activeMembership) {
            return redirect()->route('member.dashboard')->with('error', 'You already have an active membership.');
        }

        // Simulate a successful payment and create the membership
        Membership::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($plan->duration_in_days),
            'status' => 'Active',
            'payment_status' => 'Paid'
        ]);

        AuditLog::log('Purchased Membership', "Bought the {$plan->name} plan.");

        return redirect()->route('member.dashboard')->with('success', 'Payment successful! Your membership is now active.');
    }
}