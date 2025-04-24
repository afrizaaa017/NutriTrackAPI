<?php

namespace App\Http\Controllers\Api;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'weight' => 'required|numeric',
    //         'height' => 'required|numeric',
    //         'goal' => 'required|in:menaikan banyak,menaikan sedikit,menurunkan banyak,menurunkan sedikit,menjaga kestabilan',
    //     ]);

    //     $userProfile = UserProfile::create([
    //         'email' => Auth::user()->email,
    //         'weight' => $request->weight,
    //         'height' => $request->height,
    //         'goal' => $request->goal,
    //     ]);

    //     return response()->json(['message' => 'Profil berhasil dibuat', 'profile' => $userProfile], 201);
    // }

    public function updateUserProfile(Request $request)
    {
        try {
            $email = Auth::user()->email;

            $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'nullable|string|max:50',
                'weight' => 'required|numeric|min:1',
                'height' => 'required|numeric|min:1',
                'birthday' => 'required|date',
                'goal' => 'required|in:Gain a little weight,Gain a lot of weight,Lose a little weight,Lose a lot of weight,Maintain weight',
                'AMR' => 'required|in:Sedentary active,Lightly active,Moderately active,Highly active,Extremely active',
                'calories_needed' => 'required|numeric',
                'gender' => 'required|boolean',
                'image' => 'nullable|string',
            ]);
            $profile = UserProfile::findOrFail($email);

            $data = $request->only([
                'first_name',
                'last_name',
                'weight',
                'height',
                'birthday',
                'goal',
                'AMR',
                'calories_needed',
                'gender'
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile_images', 'public');
                $data['image'] = $imagePath;
            }

            $profile->update($data);

            return response()->json([
                'message' => 'Profile updated successfully.',
                'data' => $profile
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Profile not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating user profile: ' . $e->getMessage());
            return response()->json([
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }
}
