<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);
        $request->session()->put('selected_month', $request->input('month'));
        return back();
    }
}
