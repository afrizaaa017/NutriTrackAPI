<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;


class AuthController extends Controller
{
    public function signin(Request $request) {
        Log::info("Request received:", $request->all());
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            Log::info("User Logging:", ['email' => $user->email]); // Debug output

            return response()->json([
                'token' => $user->createToken('auth_token')->plainTextToken,
                'message' => 'Sign in successful',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }

    public function signup(Request $request) {
        try {
            Log::info("Request received:", $request->all()); // Debug input

            $validatedData = $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);

            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Pastikan password di-hash
            ]);

            Log::info("User created:", ['email' => $user->email]); // Debug output

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (ValidationException $e) {
            Log::error("Validation error:", $e->errors());
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error("Signup error:", ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }


    public function signout(Request $request) {
        try {
            if (!$request->user()) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $user = User::where('email', $request->email)->first();
            Log::info("Logged out:", $user->email);
            $request->user()->currentAccessToken()->delete();
            Log::info("Logged out:", $request->all());
            return response()->json([
                'message' => 'User logged out successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }
}
