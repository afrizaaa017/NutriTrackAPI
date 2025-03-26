<?php

namespace App\Http\Controllers\Api;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
