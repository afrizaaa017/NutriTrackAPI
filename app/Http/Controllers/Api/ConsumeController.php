<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Models\Consume;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConsumeController extends Controller
{
    public function store(Request $request)
    {
        $nowJakarta = Carbon::now('Asia/Jakarta');

        $request->validate([
            'email' => 'required|email',
            'food_id' => 'required|integer',
            'meal_time' => 'required',
            'portion' => 'required|integer',
            'total_sugar' => 'required|numeric',
            'total_calories' => 'required|numeric',
            'total_fat' => 'required|numeric',
            'total_carbs' => 'required|numeric',
            'total_protein' => 'required|numeric'
        ]);

         $food = Food::find($request->food_id);

        if (!$food) {
            $food = Food::create([
                'food_id' => $request->food_id, 
                'food_name' => $request->food_name, 
            ]);
        }

        $data = $request->only([
            'email', 'food_id', 'meal_time', 'portion',
            'total_sugar', 'total_calories', 'total_fat', 'total_carbs', 'total_protein'
        ]);
         
        if ($request->portion > 1) {
            $data['total_sugar'] = $request->portion * $request->total_sugar;
            $data['total_calories'] = $request->portion * $request->total_calories;
            $data['total_fat'] = $request->portion * $request->total_fat;
            $data['total_carbs'] = $request->portion * $request->total_carbs;
            $data['total_protein'] = $request->portion * $request->total_protein;
        }

        $data['consumed_at'] = $nowJakarta;

        $consume = Consume::create($data);

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'data' => $consume
        ], 201);
       
    }

    // public function getConsumesByMealTime(Request $request, $mealTime) {
    //     // Validasi bahwa pengguna telah login
    //     if (!Auth::check()) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Ambil email pengguna yang sedang login
    //     $userEmail = Auth::user()->email;

    //     // Ambil konsumsi berdasarkan email & meal_time
    //     $consumes = Consume::where('email', $userEmail)
    //         ->where('meal_time', $mealTime)
    //         ->get();

    //     return response()->json($consumes);
    // }
}
