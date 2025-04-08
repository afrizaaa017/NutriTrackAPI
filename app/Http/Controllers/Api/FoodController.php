<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Consume;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FoodController extends Controller
{
    public function getFoodRecommendationsByMealTime()
    {
        $user = Auth::user();
        $email = $user->email;
        $recommendations = [];

        foreach (['breakfast', 'lunch', 'snack', 'dinner'] as $mealTime) {
            $consumes = Consume::where('email', $email)
                ->where('meal_time', $mealTime)
                ->select('food_id', DB::raw('count(*) as food_count'))
                ->groupBy('food_id')
                ->orderByDesc('food_count')
                ->limit(5)
                ->get();

            $recommendations[$mealTime] = $consumes->map(function ($consume) use ($mealTime) {
                $food = Food::where('food_id', $consume->food_id)->first();
                $foodDetails = Consume::where('food_id', $consume->food_id)->first();
                return [
                    'food_id' => $consume->food_id,
                    'food_name' => $food->food_name,
                    'meal_time' => $mealTime,
                    'portion' => $foodDetails->portion,
                    'total_sugar' => $foodDetails->total_sugar,
                    'total_calories' => $foodDetails->total_calories,
                    'total_fat' => $foodDetails->total_fat,
                    'total_carbs' => $foodDetails->total_carbs,
                    'total_protein' => $foodDetails->total_protein
                ];
            });
        }

        return response()->json([
            'recommendations' => $recommendations
        ]);
    }

    // public function index() {
    //     return response()->json(Food::all(), 200);
    // }

    // public function store(Request $request) {
    //     $food = Food::create($request->all());
    //     return response()->json($food, 201);
    // }
}