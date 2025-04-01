<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    public function onboarding(Request $request) {

        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please sign in first.'
            ], 401);
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'birthday' => 'required|date',
            'goal' => 'required|in:Gain a little weight,Gain a lot of weight,Lose a little weight,Lose a lot of weight,Maintain weight',
            'AMR' => 'required|in:Sedentary active,Lightly active,Moderately active,Highly active, Extremely active',
            'gender' => 'required|boolean',
            'image' => 'nullable|string',
        ]);

        $email = Auth::user()->email;

        if (UserProfile::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'User profile already exists.'
            ], 409);
        }

        $caloriesNeeded = $this->calculateCaloriesNeeded(
            $validatedData['weight'],
            $validatedData['height'],
            $validatedData['birthday'],
            $validatedData['gender'],
            $validatedData['AMR'],
            $validatedData['goal']
        );

        try {
            $user = UserProfile::create([
                'email' => $email,
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'weight' => $validatedData['weight'],
                'height' => $validatedData['height'],
                'birthday' => $validatedData['birthday'],
                'goal' => $validatedData['goal'],
                'AMR' => $validatedData['AMR'],
                'calories_needed' => $caloriesNeeded,
                'gender' => $validatedData['gender'],
                'image' => $validatedData['image'] ?? null,
                'points' => 0,
            ]);

            Log::info("onboarding succes:", ['email' => $user->email]);

            return response()->json([
                'message' => 'User profile created successfully',
                'data' => $user
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to create user profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateCaloriesNeeded($weight, $height, $birthday, $gender, $amr, $goal)
    {
        $age = \Carbon\Carbon::parse($birthday)->age;

        if ($gender) { // Male: true
            $bmr = 66.5 + (13.75 * $weight) + (5 * $height) - (6.75 * $age);
        } else { // Female: false
            $bmr = 655.1 + (9.563 * $weight) + (1.85 * $height) - (4.676 * $age);
        }

        $activityFactors = [
            'Sedentary active'  => 1.2,
            'Lightly active'   => 1.375,
            'Moderately active'=> 1.55,
            'Highly active'    => 1.725,
            'Extremely active'    => 1.9,
        ];
        $bmr *= $activityFactors[$amr] ?? 1.2;

        $goalAdjustments = [
            'Gain a lot of weight'   => 500,
            'Gain a little weight'   => 250,
            'Lose a little weight'   => -250,
            'Lose a lot of weight'   => -500,
            'Maintain weight'        => 0,
        ];
        $caloriesNeeded = $bmr + ($goalAdjustments[$goal] ?? 0);

        return round($caloriesNeeded);
    }

}
