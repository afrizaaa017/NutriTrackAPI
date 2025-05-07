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
use Carbon\carbon;

class DailySummaryController extends Controller
{
    public function getDailyReport(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
            ]);

            $email = Auth::user()->email;
            $date = Carbon::parse($request->date)->toDateString();

            $exist = DailySummary::where('email', $email)
                ->where('date', $date)
                ->first();

            if (!$exist) {
                return response()->json([
                    'message' => 'No daily report found for the given date',
                    'data' => null,
                ]);
            }

            // Log::info("data retrieved:", ['data' => $exist]);

            return response()->json([
                'message' => 'Daily report retrieved successfully',
                'data' => $exist,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function showGraph(Request $request)
    {
        try {
            $email = Auth::user()->email;
            $date = Carbon::parse($request->date)->toDateString();

            $history = DailySummary::where('email', $email)
                ->where('date', '<=', $date)
                ->select('date', 'weight_recap', 'height_recap')
                ->orderBy('date', 'desc')
                ->take(7)
                ->get();

            if ($history->isEmpty()) {
                return response()->json([
                    'status' => 'empty',
                    'message' => 'No history data found for the user',
                    'data' => [],
                    'count' => 0,
                ], 200);
            }

            $formattedData = $history->map(function ($item) {
                return [
                    'date' => $item->date,
                    'weight_recap' => $item->weight_recap,
                    'height_recap' => $item->height_recap,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Weight and height history retrieved successfully',
                'data' => $formattedData,
                'count' => $history->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
                'data' => [],
                'count' => 0,
            ], 500);
        }
    }

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
        $consumed = DailySummary::where('email', $user->email)->calories_consumed;
        $remaining = $consumed - $goals;

        return response()->json([
            'calories_needed' => $goals,
            'total_consumed' => $consumed,
            'total_remaining' => $remaining
        ]);
    }
}
