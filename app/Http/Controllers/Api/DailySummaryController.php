<?php

namespace App\Http\Controllers;

use App\Models\DailySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailySummaryController extends Controller
{
    public function show()
    {
        $summary = DailySummary::where('email', Auth::user()->email)
            ->orderBy('date', 'desc')
            ->first();

        if (!$summary) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($summary);
    }
}
