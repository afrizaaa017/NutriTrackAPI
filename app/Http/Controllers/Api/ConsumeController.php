<?php

namespace App\Http\Controllers\Api;

use App\Models\Consume;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConsumeController extends Controller
{
    // public function store(Request $request)
    // {
    //     // $request->validate([
    //     //     'food_id' => 'required|exists:foods,food_id',
    //     //     'meal_time' => 'required|in:breakfast,lunch,dinner,snack',
    //     //     'portion' => 'required|integer|min:1',
    //     // ]);

    //     $consume = Consume::create($request->all());

    //     // $consume = Consume::create([
    //     //     'email' => Auth::user()->email,
    //     //     'food_id' => $request->food_id,
    //     //     'meal_time' => $request->meal_time,
    //     //     'portion' => $request->portion,
    //     //     'total_calories' => 200, 
    //     // ]);

    //     return response()->json([
    //         'message' => 'Data konsumsi berhasil disimpan',
    //         'data' => $consume
    //     ], 201);

    // }

    // public function dailyIntake()
    // {
    //     $data = Consume::where('email', Auth::user()->email)
    //         ->whereDate('created_at', today())
    //         ->get();

    //     return response()->json($data);
    // }
}
