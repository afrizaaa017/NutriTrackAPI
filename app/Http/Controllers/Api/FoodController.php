<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FoodController extends Controller
{
    public function index() {
        return response()->json(Food::all(), 200);
    }

    public function store(Request $request) {
        $food = Food::create($request->all());
        return response()->json($food, 201);
    }
}

