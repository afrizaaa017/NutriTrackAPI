<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLeaderboard;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function createUserLeaderboard(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'points' => 'required|integer|min:0',
                'streaks' => 'required|integer|min:0',
            ]);

            $email = Auth::user()->email;
            $user = UserLeaderboard::create([
                'email' => $email,
                'points' => $validatedData['points'],
                'streaks' => $validatedData['streaks'],
            ]);

            return response()->json([
                'message' => 'User leaderboard created successfully',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the leaderboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePoints(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'points' => 'required|integer|min:0',
                'streaks' => 'required|integer|min:0',
            ]);

            $email = Auth::user()->email;
            $leaderboard = UserLeaderboard::find($email);

            if (!$leaderboard) {
                return response()->json([
                    'message' => 'Leaderboard record not found for this user',
                ], 404);
            }

            $leaderboard->update([
                'points' => $leaderboard->points + $validatedData['points'],
                'streaks' => $leaderboard->streaks + $validatedData['streaks'],
            ]);

            return response()->json([
                'message' => 'Leaderboard updated successfully',
                'data' => $leaderboard,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the leaderboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetStreak()
    {
        try {
            $email = Auth::user()->email;
            $leaderboard = UserLeaderboard::find($email);
            if (!$leaderboard) {
                return response()->json([
                    'message' => 'Leaderboard record not found for this user',
                ], 404);
            }
            $leaderboard->update([
                'streaks' => 0,
            ]);
            return response()->json([
                'message' => 'Streak reset successfully',
                'data' => $leaderboard,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while resetting the streak',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // reset season
    public function resetPoints()
    {
        try {
            $email = Auth::user()->email;
            $leaderboard = UserLeaderboard::find($email);
            if (!$leaderboard) {
                return response()->json([
                    'message' => 'Leaderboard record not found for this user',
                ], 404);
            }
            $leaderboard->update([
                'points' => 0,
                'streaks' => 0,
            ]);
            return response()->json([
                'message' => 'Point reset successfully',
                'data' => $leaderboard,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while resetting the streak',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // jika nilai ingin tetap, tidak di update, maka tidak perlu menyertakan nilai di parameter.
    public function updateUserLeaderboard(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'points' => 'nullable|integer|min:0',
                'streaks' => 'nullable|integer|min:0',
            ]);

            $email = Auth::user()->email;
            $leaderboard = UserLeaderboard::find($email);

            if (!$leaderboard) {
                return response()->json([
                    'message' => 'Leaderboard record not found for this user',
                ], 404);
            }

            $updateData = [];

            if (isset($validatedData['points'])) {
                if ($validatedData['points'] == 0) {
                    $updateData['points'] = 0;
                } else {
                    $updateData['points'] = $leaderboard->points + $validatedData['points'];
                }
            }

            if (isset($validatedData['streaks'])) {
                if ($validatedData['streaks'] == 0) {
                    $updateData['streaks'] = 0;
                } else {
                    $updateData['streaks'] = $leaderboard->streaks + $validatedData['streaks'];
                }
            }

            if (!empty($updateData)) {
                $leaderboard->update($updateData);
            }

            return response()->json([
                'message' => 'Leaderboard updated successfully',
                'data' => $leaderboard,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the leaderboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
