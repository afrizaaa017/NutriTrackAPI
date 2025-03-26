<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsumeController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DailySummaryController;
use App\Http\Controllers\Api\AuthController;

Route::post('/profile', [UserProfileController::class, 'store']);
Route::get('/daily-summary', [DailySummaryController::class, 'show']);
Route::post('/consume', [ConsumeController::class, 'store']);
Route::get('/consume/daily-intake', [ConsumeController::class, 'dailyIntake']);

//auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
