<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    // getEstimate
    public function getEstimate(Request $request)
    {
        // estimate page
        return view('v2.estimation_old');
    }

    // getEstimate2
    public function getEstimate2(Request $request)
    {
        // estimate page
        return view('v2.estimate');
    }
}
