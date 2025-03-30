<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConsumeController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\DailySummaryController;

// Route::post('/profile', [UserProfileController::class, 'store']);
// Route::get('/daily-summary', [DailySummaryController::class, 'show']);

Route::post('/consume', [ConsumeController::class, 'store']);
// Route::get('/consumes/{mealTime}', [ConsumeController::class, 'getConsumesByMealTime']);
