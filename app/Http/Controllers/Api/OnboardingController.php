<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;

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
            'goal' => 'required|in:gain a little weight,gain a lot of weight,lose a little weight,lose a lot of weight,maintain',
            'AMR' => 'required|in:sedentary active,lightly active,moderately active,highly active',
            'calories_needed' => 'required|numeric|min:0',
            'gender' => 'required|boolean',
            'image' => 'nullable|string',
            'points' => 'required|integer|min:0',
        ]);

        $email = Auth::user()->email;

        if (UserProfile::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'User profile already exists.'
            ], 409);
        }

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
                'calories_needed' => $validatedData['calories_needed'],
                'gender' => $validatedData['gender'],
                'image' => $validatedData['image'] ?? null,
                'points' => $validatedData['points'],
            ]);

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
}
