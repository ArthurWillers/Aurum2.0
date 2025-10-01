<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        // Pega o mês da sessão ou usa o mês atual
        $selectedMonth = session('selected_month', now()->format('Y-m'));

        // Busca a soma das receitas e despesas do mês selecionado
        $incomes = Auth::user()->transactions()
            ->where('type', 'income')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->sum('amount');
        
        // Busca a soma das despesas do mês selecionado
        $expenses = Auth::user()->transactions()
            ->where('type', 'expense')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->sum('amount');

        // Busca a soma das despesas por categoria para o gráfico
        $expensesByCategory = Auth::user()->transactions()
            ->where('type', 'expense')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($transactions) {
                return [
                    'category' => $transactions->first()->category,
                    'total' => $transactions->sum('amount'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Busca a soma das receitas por categoria para o gráfico
        $incomesByCategory = Auth::user()->transactions()
            ->where('type', 'income')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($transactions) {
                return [
                    'category' => $transactions->first()->category,
                    'total' => $transactions->sum('amount'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('dashboard', compact('incomes', 'expenses', 'expensesByCategory', 'incomesByCategory'));
    }
}
