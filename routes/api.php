<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConsumeController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\DailySummaryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OnboardingController;

// Route::post('/profile', [UserProfileController::class, 'store']);
// Route::get('/daily-summary', [DailySummaryController::class, 'show']);

Route::post('/consume', [ConsumeController::class, 'store']);

//auth
Route::post('/signin', [AuthController::class, 'signin']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::delete('/signout', [AuthController::class, 'signout'])->middleware(['auth:sanctum']);
// password reset
Route::post('/check-and-update-password', [AuthController::class, 'checkAndUpdatePassword']);

// oboarding
Route::post('/onboarding', [OnboardingController::class, 'onboarding'])->middleware(['auth:sanctum']);
