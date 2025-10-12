<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <x-button href="{{ route('transactions.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nova Transação
        </x-button>
    </div>

    {{-- Conteúdo --}}
    <div class="space-y-8 lg:mb-5">
        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $selectedMonth = session('selected_month', now()->format('Y-m'));
                $monthYear = \Carbon\Carbon::parse($selectedMonth)->locale('pt_BR')->isoFormat('MMMM [de] YYYY');
            @endphp

            {{-- Card Receitas do Mês --}}
            <a href="{{ route('incomes.index') }}"
                class="bg-white rounded-lg shadow-lg p-6 border border-neutral-200 hover:shadow-xl transition-shadow duration-200 block">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                            <p class="text-sm font-medium text-neutral-600">Receitas de
                                {{ ucfirst($monthYear) }}</p>
                            @if ($incomesChange != 0)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $incomesChange > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} max-w-fit"
                                    title="R$ {{ number_format(abs($incomesDiff), 2, ',', '.') }} {{ $incomesDiff > 0 ? 'a mais' : 'a menos' }} que o mês anterior">
                                    @if ($incomesChange > 0)
                                        <svg class="w-3 h-3 mr-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                    @endif
                                    <span>R$ {{ number_format(abs($incomesDiff), 0, ',', '.') }}
                                        ({{ number_format(abs($incomesChange), 1) }}%)</span>
                                </span>
                            @endif
                        </div>
                        <p class="text-xl sm:text-2xl font-semibold text-green-600 break-words">
                            R$ {{ number_format($incomes, 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Card Despesas do Mês --}}
            <a href="{{ route('expenses.index') }}"
                class="bg-white rounded-lg shadow-lg p-6 border border-neutral-200 hover:shadow-xl transition-shadow duration-200 block">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                            <p class="text-sm font-medium text-neutral-600">Despesas de
                                {{ ucfirst($monthYear) }}</p>
                            @if ($expensesChange != 0)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $expensesChange > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} max-w-fit"
                                    title="R$ {{ number_format(abs($expensesDiff), 2, ',', '.') }} {{ $expensesDiff > 0 ? 'a mais' : 'a menos' }} que o mês anterior">
                                    @if ($expensesChange > 0)
                                        <svg class="w-3 h-3 mr-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                    @endif
                                    <span>R$ {{ number_format(abs($expensesDiff), 0, ',', '.') }}
                                        ({{ number_format(abs($expensesChange), 1) }}%)</span>
                                </span>
                            @endif
                        </div>
                        <p class="text-xl sm:text-2xl font-semibold text-red-600 break-words">
                            R$ {{ number_format($expenses, 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Card Saldo do Mês --}}
            <div class="bg-white rounded-lg shadow-lg p-6 border border-neutral-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                            <p class="text-sm font-medium text-neutral-600">Saldo de
                                {{ ucfirst($monthYear) }}</p>
                            @if ($balanceChange != 0)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $balanceChange > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} max-w-fit"
                                    title="R$ {{ number_format(abs($balanceDiff), 2, ',', '.') }} {{ $balanceDiff > 0 ? 'a mais' : 'a menos' }} que o mês anterior">
                                    @if ($balanceChange > 0)
                                        <svg class="w-3 h-3 mr-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        @else
                                            <svg class="w-3 h-3 mr-0.5 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                    @endif
                                    <span>R$ {{ number_format(abs($balanceDiff), 0, ',', '.') }}
                                        ({{ number_format(abs($balanceChange), 1) }}%)</span>
                                </span>
                            @endif
                        </div>
                        <p
                            class="text-xl sm:text-2xl font-semibold {{ $incomes - $expenses >= 0 ? 'text-green-600' : 'text-red-600' }} break-words">
                            R$ {{ number_format($incomes - $expenses, 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 {{ $incomes - $expenses >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 sm:w-6 sm:h-6 {{ $incomes - $expenses >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico de Despesas por Categoria --}}
        @if ($expensesByCategory->isNotEmpty() && $expenses > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-neutral-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-800">Top {{ $expensesByCategory->count() }} Categorias -
                        Despesas</h3>
                </div>

                <div class="space-y-6">
                    @foreach ($expensesByCategory as $index => $item)
                        @php
                            $percentage = ($item['total'] / $expenses) * 100;
                        @endphp

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <span class="text-red-600 font-bold text-sm">{{ $index + 1 }}</span>
                            </div>

                            {{-- Conteúdo da categoria --}}
                            <div class="flex-1 min-w-0">
                                {{-- Nome e porcentagem --}}
                                <div class="flex items-baseline justify-between mb-2 gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-semibold text-neutral-800 truncate">
                                            <a href="{{ route('expenses.index', ['category_id' => $item['category']->id]) }}"
                                                class="hover:text-red-600 hover:underline transition-colors">
                                                {{ $item['category']->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-neutral-500">
                                            {{ number_format($percentage, 1) }}% do total
                                        </p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        {{-- Valor total da categoria --}}
                                        <p class="text-base font-bold text-neutral-800">
                                            R$ {{ number_format($item['total'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="w-full bg-red-50 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-500 to-red-600 h-full rounded-full transition-all duration-700 ease-out"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Gráfico de Receitas por Categoria --}}
        @if ($incomesByCategory->isNotEmpty() && $incomes > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-neutral-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-800">Top {{ $incomesByCategory->count() }} Categorias -
                        Receitas</h3>
                </div>

                <div class="space-y-6">
                    @foreach ($incomesByCategory as $index => $item)
                        @php
                            $percentage = ($item['total'] / $incomes) * 100;
                        @endphp

                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-green-600 font-bold text-sm">{{ $index + 1 }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline justify-between mb-2 gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-semibold text-neutral-800 truncate">
                                            <a href="{{ route('incomes.index', ['category_id' => $item['category']->id]) }}"
                                                class="hover:text-green-600 hover:underline transition-colors">
                                                {{ $item['category']->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-neutral-500">
                                            {{ number_format($percentage, 1) }}% do total
                                        </p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        {{-- Valor total da categoria --}}
                                        <p class="text-base font-bold text-neutral-800">
                                            R$ {{ number_format($item['total'], 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="w-full bg-green-50 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full transition-all duration-700 ease-out"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Gráfico de Linhas - Histórico Mensal --}}
        @if (!empty($chartData) && count($chartData) > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-neutral-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-800">Evolução Financeira</h3>
                </div>

                <div class="relative" style="height: 300px;">
                    <canvas id="financialEvolutionChart"></canvas>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const chartData = @json($chartData);
                    window.initFinancialEvolutionChart(chartData);
                });
            </script>
        @endif
    </div>
</x-layouts.app>
