<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\Consume;
use App\Models\DailySummary;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Attributes\Log;

class ConsumeController extends Controller
{
    public function store(Request $request)
    {
        $nowJakarta = Carbon::now('Asia/Jakarta')->copy()->addDay(5);
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

        $data['total_sugar'] = $request->portion * $request->total_sugar;
        $data['total_calories'] = $request->portion * $request->total_calories;
        $data['total_fat'] = $request->portion * $request->total_fat;
        $data['total_carbs'] = $request->portion * $request->total_carbs;
        $data['total_protein'] = $request->portion * $request->total_protein;
        $data['consumed_at'] = $nowJakarta;

        if ($consume = Consume::create($data)) {
            $date = $nowJakarta->toDateString();

            $summary = DailySummary::where('email', $request->email)
            ->where('date', $date)
            ->first();

            $userProfile = UserProfile::where('email', $request->email)->first();
            $targetCalories = $userProfile ? $userProfile->calories_needed : 0;


            if (!$summary) {
                DailySummary::create([
                    'email' => $request->email,
                    'date' => $date,
                    'target_calories' => $targetCalories,
                    'calories_consumed' => round($data['total_calories']),
                    'fat_consumed' => round($data['total_fat']),
                    'sugar_consumed' => round($data['total_sugar']),
                    'carbs_consumed' => round($data['total_carbs']),
                    'protein_consumed' => round($data['total_protein']),
                    'created_at' => $nowJakarta,
                ]);
            } else {
                DailySummary::where('email', $request->email)
                ->where('date', $date)
                ->update([
                    'calories_consumed' => round($summary->calories_consumed + $data['total_calories']),
                    'fat_consumed' => round($summary->fat_consumed + $data['total_fat']),
                    'sugar_consumed' => round($summary->sugar_consumed + $data['total_sugar']),
                    'carbs_consumed' => round($summary->carbs_consumed + $data['total_carbs']),
                    'protein_consumed' => round($summary->protein_consumed + $data['total_protein']),
                    'updated_at' => $nowJakarta
                ]);
            }
        }

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'data' => $consume
        ], 201);

    }

    public function getByMealTime(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'meal_time' => 'required|in:breakfast,lunch,dinner,snack',
        ]);

        $consumes = Consume::where('email', $request->email)
            ->where('meal_time', $request->meal_time)
            ->with('food') // jika ada relasi dengan tabel `foods`
            ->get();

        return response()->json($consumes);
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
