<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Consume;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FoodController extends Controller
{
    public function getFoodRecommendationsByMealTime(Request $request)
    {
        $user = Auth::user();
        $email = $user->email;
        $currentHour = Carbon::now()->hour;
        
        $mealTimes = [
            'breakfast' => ['start' => 5, 'end' => 9],
            'lunch' => ['start' => 10, 'end' => 13],
            'snack' => ['start' => 14, 'end' => 17],
            'dinner' => ['start' => 18, 'end' => 4]
        ];

        foreach ($mealTimes as $mealTime => $timeRange) {
            if (($currentHour >= $timeRange['start'] && $currentHour <= $timeRange['end']) || 
                ($mealTime == 'dinner' && ($currentHour >= 18 || $currentHour < 5))) {
                break;
            }
        }

        $sevenDaysAgo = Carbon::now()->subDays(7);
        $recommendations = [];

        foreach (['breakfast', 'lunch', 'snack', 'dinner'] as $mealTime) {
            $consumes = Consume::where('email', $email)
                ->where('meal_time', $mealTime)
                ->where('consumed_at', '>=', $sevenDaysAgo)
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