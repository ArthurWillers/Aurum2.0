<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, String $type)
    {
        $this->authorize('viewAny', Transaction::class);

        $title = $type === 'income' ? 'Receitas' : 'Despesas';

        // Pega o mês da sessão ou usa o mês atual
        $selectedMonth = session('selected_month', now()->format('Y-m'));

        $query = Auth::user()->transactions()
            ->where('type', $type)
            ->whereYear('date', Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', Carbon::parse($selectedMonth)->month)
            ->with('category');

        // Aplica filtro por categoria se fornecido
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Verifica se deve usar paginação
        if ($request->has('show_all')) {
            $transactions = $query
                ->latest('date')
                ->latest('updated_at')
                ->get();
        } else {
            $transactions = $query
                ->latest('date')
                ->latest('updated_at')
                ->paginate(9)
                ->withQueryString();
        }

        // Busca todas as categorias do usuário para o filtro
        $categories = Auth::user()->categories()
            ->where('type', $type)
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact('transactions', 'type', 'title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(?string $type = null)
    {
        $this->authorize('create', Transaction::class);

        $categories = Auth::user()->categories()->orderBy('name')->get();

        $backRoute = $type === 'income' ? 'incomes.index' : ($type === 'expense' ? 'expenses.index' : 'dashboard');
        return view('transactions.create', compact('categories', 'type', 'backRoute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $this->authorize('create', Transaction::class);

        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        switch ($validated['transaction_type']) {
            case 'single':
                $this->createSingleTransaction($validated);
                break;

            case 'recurring':
                $this->createRecurringTransactions($validated);
                break;

            case 'installment':
                $this->createInstallmentTransactions($validated);
                break;
        }

        $route = $validated['type'] === 'income' ? 'incomes.index' : 'expenses.index';

        return redirect()->route($route)->with('toast', ['message' => 'Transação criada com sucesso!', 'type' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $categories = Auth::user()->categories()->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validated();

        // Converte vírgula para ponto no valor, se necessário
        if (isset($validated['amount'])) {
            $validated['amount'] = str_replace(',', '.', $validated['amount']);
        }

        $transaction->update($validated);

        $route = $validated['type'] === 'income' ? 'incomes.index' : 'expenses.index';

        return redirect()->route($route)->with('toast', [
            'message' => 'Transação atualizada com sucesso!',
            'type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->back()->with('toast', ['message' => 'Transação Removida com sucesso!', 'type' => 'success']);
    }

    /**
     * Create a single transaction.
     */
    private function createSingleTransaction(array $data): void
    {
        Transaction::create([
            'description' => $data['description'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'date' => $data['date'],
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
        ]);
    }

    /**
     * Create recurring transactions.
     */
    private function createRecurringTransactions(array $data): void
    {
        $groupUuid = Str::uuid();
        $startDate = Carbon::parse($data['date']);

        for ($i = 0; $i < $data['recurring_months']; $i++) {
            $transactionDate = $startDate->copy()->addMonths($i);

            Transaction::create([
                'description' => $data['description'],
                'amount' => $data['amount'],
                'type' => $data['type'],
                'date' => $transactionDate->format('Y-m-d'),
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'],
                'transaction_group_uuid' => $groupUuid,
            ]);
        }
    }

    /**
     * Create installment transactions.
     */
    private function createInstallmentTransactions(array $data): void
    {
        $groupUuid = Str::uuid();
        $startDate = Carbon::parse($data['date']);
        $installmentAmount = round($data['total_amount'] / $data['installments'], 2);

        for ($i = 1; $i <= $data['installments']; $i++) {
            $transactionDate = $startDate->copy()->addMonths($i - 1);

            Transaction::create([
                'description' => $data['description'],
                'amount' => $installmentAmount,
                'type' => $data['type'],
                'date' => $transactionDate->format('Y-m-d'),
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'],
                'transaction_group_uuid' => $groupUuid,
                'installment_number' => $i,
                'total_installments' => $data['installments'],
            ]);
        }
    }
}
