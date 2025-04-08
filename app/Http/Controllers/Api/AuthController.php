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
                    'password' => 'The provided credentials are incorrect.',
                ]);
            }
            Log::info("User Logging:", ['email' => $user->email]); // Debug output

            return response()->json([
                'token' => $user->createToken('auth_token')->plainTextToken,
                'message' => 'Sign in successful',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'token' => 'null',
                'message' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'token' => 'null',
                'message' => 'Something went wrong. Please try again.'], 500);
        }
    }

    public function signup(Request $request) {
        try {
            Log::info("Request received:", $request->all()); // Debug input

            $validatedData = $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Pastikan password di-hash
            ]);

            Log::info("User created:", ['email' => $user->email]); // Debug output

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
            ], 201);
        } catch (ValidationException $e) {
            Log::error("Validation error:", $e->errors());
            return response()->json([
                'success' => false,
                'message' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error("Signup error:", ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'], 500);
        }
    }


    public function signout(Request $request) {
        try {
            if (!$request->user()) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $user = User::where('email', $request->user()->email)->first();

            if ($user) {
                Log::info("Logged out:", ['email' => $user->email]);
            }

            if ($request->user()->tokens()->delete()) {
                Log::info("Token deleted successfully");
            };

            return response()->json([
                'message' => 'User logged out successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error("Logout error:", ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong. Please try again.'], 500);
        }
    }

    public function checkAndUpdatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            Log::info("Password up to date");
            return response()->json(['message' => 'Password is already up to date']);
        } else {
            $user->password = Hash::make($request->password);
            $user->save();
            Log::info("Password changed");
            return response()->json(['message' => 'Password updated successfully']);
        }
    }

}
