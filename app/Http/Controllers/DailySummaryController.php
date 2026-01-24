<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailySummaryController extends Controller
{
    /**
     * Display the daily summary for a specific date.
     */
    public function __invoke(Request $request, ?string $date = null)
    {
        // Se nenhuma data foi fornecida, usa a data atual
        $selectedDate = $date ? Carbon::parse($date) : Carbon::today();

        // Busca todas as transações do usuário para a data selecionada
        $transactions = Transaction::where('user_id', Auth::id())
            ->whereDate('date', $selectedDate)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        // Separa em receitas e despesas
        $incomes = $transactions->where('type', 'income');
        $expenses = $transactions->where('type', 'expense');

        // Calcula os totais
        $totalIncomes = $incomes->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $balance = $totalIncomes - $totalExpenses;

        return view('daily-summary', [
            'selectedDate' => $selectedDate,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance,
            'hasTransactions' => $transactions->isNotEmpty(),
        ]);
    }
}
