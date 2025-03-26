<?php

namespace App\Http\Controllers\Api;

use App\Models\DailySummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DailySummaryController extends Controller
{
    // public function show()
    // {
    //     $summary = DailySummary::where('email', Auth::user()->email)
    //         ->orderBy('date', 'desc')
    //         ->first();

    //     if (!$summary) {
    //         return response()->json(['message' => 'Data tidak ditemukan'], 404);
    //     }

    //     return response()->json($summary);
    // }
}
