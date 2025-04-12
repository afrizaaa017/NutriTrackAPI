<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Consume;
use App\Models\DailySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class DailySummaryController extends Controller
{
    public function getDailySummary(Request $request)
    {
        $email = $request->query('email');

        // $request->validate([
        //     'email' => 'required|email',
        // ]);

        if (!$email) {
            return response()->json(['message' => 'Email parameter is missing'], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $goals = UserProfile::where('email', $email)->first()->calories_needed;
        $consumed = Consume::where('email', $user->email)->sum('total_calories');
        $remaining = $consumed - $goals;

        return response()->json([
            'calories_needed' => $goals,
            'total_consumed' => $consumed,
            'total_remaining' => $remaining
        ]);
    }
}
