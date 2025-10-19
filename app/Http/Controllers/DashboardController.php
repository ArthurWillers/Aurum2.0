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
        
        // Formata o mês/ano para exibição
        $monthYear = Carbon::parse($selectedMonth)->isoFormat('MMMM [de] YYYY');

        // Pega o mês anterior
        $previousMonth = Carbon::parse($selectedMonth)->subMonth();

        // Busca a soma das receitas do mês selecionado
        $incomes = Auth::user()->transactions()
            ->where('type', 'income')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->sum('amount');

        // Busca a soma das receitas do mês anterior
        $previousIncomes = Auth::user()->transactions()
            ->where('type', 'income')
            ->whereYear('date', $previousMonth->year)
            ->whereMonth('date', $previousMonth->month)
            ->sum('amount');

        // Busca a soma das despesas do mês selecionado
        $expenses = Auth::user()->transactions()
            ->where('type', 'expense')
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->sum('amount');

        // Busca a soma das despesas do mês anterior
        $previousExpenses = Auth::user()->transactions()
            ->where('type', 'expense')
            ->whereYear('date', $previousMonth->year)
            ->whereMonth('date', $previousMonth->month)
            ->sum('amount');

        // Calcula as variações percentuais e absolutas
        $incomesDiff = $incomes - $previousIncomes;
        $incomesChange = $previousIncomes > 0 ? ($incomesDiff / $previousIncomes) * 100 : ($incomes > 0 ? 100 : 0);

        $expensesDiff = $expenses - $previousExpenses;
        $expensesChange = $previousExpenses > 0 ? ($expensesDiff / $previousExpenses) * 100 : ($expenses > 0 ? 100 : 0);

        $balance = $incomes - $expenses;
        $previousBalance = $previousIncomes - $previousExpenses;
        $balanceDiff = $balance - $previousBalance;
        $balanceChange = $previousBalance != 0 ? ($balanceDiff / abs($previousBalance)) * 100 : ($balance > 0 ? 100 : ($balance < 0 ? -100 : 0));

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

        // Dados para o gráfico de evolução - 3 meses anteriores, atual e próximo
        $chartData = $this->getFinancialEvolutionData();
        
        // Verifica se há dados reais no gráfico (pelo menos um mês com transações)
        $hasChartData = collect($chartData)->contains(function ($month) {
            return $month['incomes'] > 0 || $month['expenses'] > 0;
        });

        return view('dashboard', compact('incomes', 'expenses', 'expensesByCategory', 'incomesByCategory', 'incomesChange', 'expensesChange', 'balanceChange', 'incomesDiff', 'expensesDiff', 'balanceDiff', 'chartData', 'hasChartData', 'monthYear'));
    }

    /**
     * Busca os dados de evolução financeira para o gráfico dos últimos 3 meses, mês atual e próximo mês
     *
     * @return array Dados formatados para o gráfico de evolução financeira
     */
    private function getFinancialEvolutionData()
    {
        // Obter o mês selecionado da sessão ou usar o mês atual
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        $currentMonthDate = Carbon::parse($selectedMonth);

        // Definir o intervalo de datas - 3 meses anteriores até o próximo mês (5 meses)
        $startDate = $currentMonthDate->copy()->subMonths(3)->startOfMonth();
        $endDate = $currentMonthDate->copy()->addMonth()->endOfMonth();

        // Buscar todas as transações de uma vez para evitar múltiplas consultas
        $transactions = Auth::user()->transactions()
            ->select('type', 'amount', 'date')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        // Preparar array para armazenar os dados por mês
        $monthlyData = [];

        // Processar os dados para cada mês no intervalo
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $yearMonth = $currentDate->format('Y-m');
            $monthFormatted = ucfirst($currentDate->locale('pt_BR')->isoFormat('MMMM/YYYY'));

            // Inicializar dados do mês se ainda não existirem
            if (! isset($monthlyData[$yearMonth])) {
                $monthlyData[$yearMonth] = [
                    'month' => $monthFormatted,
                    'incomes' => 0,
                    'expenses' => 0,
                    'balance' => 0,
                ];
            }

            // Avançar para o próximo mês
            $currentDate->addMonth();
        }

        // Processar as transações e somá-las ao mês correspondente
        foreach ($transactions as $transaction) {
            $transactionMonth = Carbon::parse($transaction->date)->format('Y-m');

            if (isset($monthlyData[$transactionMonth])) {
                if ($transaction->type === 'income') {
                    $monthlyData[$transactionMonth]['incomes'] += $transaction->amount;
                } else {
                    $monthlyData[$transactionMonth]['expenses'] += $transaction->amount;
                }
            }
        }

        // Calcular o saldo para cada mês
        foreach ($monthlyData as &$data) {
            $data['balance'] = $data['incomes'] - $data['expenses'];
        }

        // Retornar apenas os valores do array, mantendo a ordem
        return array_values($monthlyData);
    }
}
