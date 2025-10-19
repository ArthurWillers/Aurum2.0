<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Dashboard" :action="route('transactions.create')" action-text="Nova Transação" icon="plus" />

    {{-- Conteúdo --}}
    <div class="space-y-8 lg:mb-5">
        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Card Receitas do Mês --}}
            <x-card :href="route('incomes.index')" class="hover:shadow-xl transition-shadow duration-200">
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
                                        <x-icon name="arrow-up-solid" class="w-3 h-3 mr-0.5 flex-shrink-0" />
                                    @else
                                        <x-icon name="arrow-down-solid" class="w-3 h-3 mr-0.5 flex-shrink-0" />
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
                        <x-icon name="arrow-trending-up" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" />
                    </div>
                </div>
            </x-card>

            {{-- Card Despesas do Mês --}}
            <x-card :href="route('expenses.index')" class="hover:shadow-xl transition-shadow duration-200">
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
                                        <x-icon name="arrow-up-solid" class="w-3 h-3 mr-0.5 flex-shrink-0" />
                                    @else
                                        <x-icon name="arrow-down-solid" class="w-3 h-3 mr-0.5 flex-shrink-0" />
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
                        <x-icon name="arrow-trending-down" class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" />
                    </div>
                </div>
            </x-card>

            {{-- Card Saldo do Mês --}}
            <x-card>
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
                                        <x-icon name="arrow-up-solid" class="w-3 h-3 mr-0.5 flex-shrink-0" />
                                    @else
                                        <x-icon name="arrow-down-solid" class="w-3 h-3 mr-0.5 flex-shrink-0" />
                                    @endif
                                    <span>R$ {{ number_format(abs($balanceDiff), 0, ',', '.') }}
                                        ({{ number_format(abs($balanceChange), 1) }}%)</span>
                                </span>
                            @endif
                        </div>
                        <p
                            class="text-xl sm:text-2xl font-semibold {{ $incomes - $expenses >= 0 ? 'text-green-600' : 'text-red-600' }} break-words">
                            {{ $incomes - $expenses >= 0 ? '+' : '-' }}R$
                            {{ number_format(abs($incomes - $expenses), 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 {{ $incomes - $expenses >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-icon name="scale"
                            class="w-5 h-5 sm:w-6 sm:h-6 {{ $incomes - $expenses >= 0 ? 'text-green-600' : 'text-red-600' }}" />
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Gráfico de Despesas por Categoria --}}
        @if ($expensesByCategory->isNotEmpty() && $expenses > 0)
            <x-card>
                <x-section-header icon="chart-bar" iconColor="red"
                    title="Top {{ $expensesByCategory->count() }} Categorias - Despesas" />

                <div class="space-y-6 mt-6">
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
            </x-card>
        @endif

        {{-- Gráfico de Receitas por Categoria --}}
        @if ($incomesByCategory->isNotEmpty() && $incomes > 0)
            <x-card>
                <x-section-header icon="chart-bar" iconColor="green"
                    title="Top {{ $incomesByCategory->count() }} Categorias - Receitas" />

                <div class="space-y-6 mt-6">
                    @foreach ($incomesByCategory as $index => $item)
                        @php
                            $percentage = ($item['total'] / $incomes) * 100;
                        @endphp

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
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
            </x-card>
        @endif

        {{-- Gráfico de Evolução Financeira --}}
        @if ($hasChartData)
            <x-card>
                <x-section-header icon="scale" iconColor="blue" title="Evolução Financeira" />

                <div class="relative mt-6" style="height: 300px;">
                    <canvas id="financialEvolutionChart"></canvas>
                </div>
            </x-card>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const chartData = @json($chartData);
                    window.initFinancialEvolutionChart(chartData);
                });
            </script>
        @endif
    </div>
</x-layouts.app>
