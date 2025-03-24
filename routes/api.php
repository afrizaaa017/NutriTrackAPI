<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsumeController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DailySummaryController;

Route::post('/profile', [UserProfileController::class, 'store']);
Route::get('/daily-summary', [DailySummaryController::class, 'show']);
Route::post('/consume', [ConsumeController::class, 'store']);
Route::get('/consume/daily-intake', [ConsumeController::class, 'dailyIntake']);
