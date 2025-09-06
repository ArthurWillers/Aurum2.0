<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        // Pega o mês da sessão ou usa o mês atual
        $selectedMonth = session('selected_month', now()->format('Y-m'));

        $incomes = Auth::user()->transactions()
            ->where('type', 'income')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->sum('amount');
        $expenses = Auth::user()->transactions()
            ->where('type', 'expense')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->sum('amount');

        return view('dashboard', compact('incomes', 'expenses'));
    }
}
