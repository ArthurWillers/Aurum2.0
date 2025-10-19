<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Resumo Diário" />

    {{-- Seletor de Data --}}
    <div class="mb-6">
        <div class="max-w-xs">
            <x-form-input type="date" name="date" id="date-selector" label="Selecione uma data" :value="$selectedDate->format('Y-m-d')"
                max="{{ now()->format('Y-m-d') }}"
                onchange="window.location.href = '{{ route('daily-summary') }}/' + this.value" />
        </div>
    </div>

    {{-- Data Selecionada --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-neutral-800">
            {{ $selectedDate->isoFormat('D [de] MMMM [de] YYYY') }}
        </h2>
        @if ($selectedDate->isToday())
            <p class="text-sm text-neutral-600 mt-1">Hoje</p>
        @elseif ($selectedDate->isYesterday())
            <p class="text-sm text-neutral-600 mt-1">Ontem</p>
        @endif
    </div>

    @if ($hasTransactions)
        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            {{-- Card Receitas do Dia --}}
            <x-card>
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-600 mb-2">Total de Receitas</p>
                        <p class="text-xl sm:text-2xl font-semibold text-green-600 break-words">
                            R$ {{ number_format($totalIncomes, 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-icon name="arrow-trending-up" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" />
                    </div>
                </div>
            </x-card>

            {{-- Card Despesas do Dia --}}
            <x-card>
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-600 mb-2">Total de Despesas</p>
                        <p class="text-xl sm:text-2xl font-semibold text-red-600 break-words">
                            R$ {{ number_format($totalExpenses, 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-icon name="arrow-trending-down" class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" />
                    </div>
                </div>
            </x-card>

            {{-- Card Saldo do Dia --}}
            <x-card>
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-neutral-600 mb-2">Saldo do Dia</p>
                        <p
                            class="text-xl sm:text-2xl font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }} break-words">
                            {{ $balance >= 0 ? '+' : '-' }}R$ {{ number_format(abs($balance), 2, ',', '.') }}
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 {{ $balance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-icon name="scale"
                            class="w-5 h-5 sm:w-6 sm:h-6 {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}" />
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Listas de Transações --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:mb-8">
            {{-- Receitas --}}
            <div>
                @if ($incomes->count() > 0)
                    <div class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">
                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-neutral-200 bg-green-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <x-icon name="arrow-trending-up" class="w-5 h-5 text-green-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-800">Receitas</h3>
                                    <p class="text-sm text-neutral-600">{{ $incomes->count() }}
                                        {{ $incomes->count() === 1 ? 'transação' : 'transações' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Lista --}}
                        <div class="divide-y divide-neutral-200">
                            @foreach ($incomes as $transaction)
                                <div class="relative p-4">
                                    <form method="POST" id="delete-form-{{ $transaction->id }}"
                                        action="{{ route('transactions.destroy', $transaction) }}"
                                        onsubmit="return confirm('Você tem certeza que deseja excluir esta transação?');">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-base font-semibold text-neutral-900 leading-tight mb-1">
                                                {{ $transaction->description }}
                                                @if ($transaction->total_installments)
                                                    <span class="text-sm text-neutral-500 font-normal">
                                                        ({{ $transaction->installment_number }}/{{ $transaction->total_installments }})
                                                    </span>
                                                @endif
                                            </h4>
                                            <div class="text-sm text-neutral-600 mb-2">
                                                {{ $transaction->category->name ?? 'Sem categoria' }}
                                            </div>
                                            <div class="text-lg font-semibold text-green-600">
                                                +R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <x-dropdown position="bottom-end" accent>
                                                <x-slot name="trigger">
                                                    <button
                                                        class="cursor-pointer rounded-md border border-neutral-300 p-2 transition duration-150 ease-in-out hover:bg-neutral-100">
                                                        <x-icon name="ellipsis-horizontal" class="w-6 h-6" />
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                                        <x-icon name="pencil" class="w-5 h-5" />
                                                        Editar
                                                    </a>
                                                    <button type="submit" form="delete-form-{{ $transaction->id }}"
                                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                                        <x-icon name="trash" class="w-5 h-5" />
                                                        Excluir
                                                    </button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">
                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-neutral-200 bg-green-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <x-icon name="arrow-trending-up" class="w-5 h-5 text-green-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-800">Receitas</h3>
                                    <p class="text-sm text-neutral-600">0 transações</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-neutral-500 text-center py-4">Nenhuma receita registrada neste dia.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Despesas --}}
            <div>
                @if ($expenses->count() > 0)
                    <div class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">
                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-neutral-200 bg-red-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <x-icon name="arrow-trending-down" class="w-5 h-5 text-red-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-800">Despesas</h3>
                                    <p class="text-sm text-neutral-600">{{ $expenses->count() }}
                                        {{ $expenses->count() === 1 ? 'transação' : 'transações' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Lista --}}
                        <div class="divide-y divide-neutral-200">
                            @foreach ($expenses as $transaction)
                                <div class="relative p-4">
                                    <form method="POST" id="delete-form-{{ $transaction->id }}"
                                        action="{{ route('transactions.destroy', $transaction) }}"
                                        onsubmit="return confirm('Você tem certeza que deseja excluir esta transação?');">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-base font-semibold text-neutral-900 leading-tight mb-1">
                                                {{ $transaction->description }}
                                                @if ($transaction->total_installments)
                                                    <span class="text-sm text-neutral-500 font-normal">
                                                        ({{ $transaction->installment_number }}/{{ $transaction->total_installments }})
                                                    </span>
                                                @endif
                                            </h4>
                                            <div class="text-sm text-neutral-600 mb-2">
                                                {{ $transaction->category->name ?? 'Sem categoria' }}
                                            </div>
                                            <div class="text-lg font-semibold text-red-600">
                                                -R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <x-dropdown position="bottom-end" accent>
                                                <x-slot name="trigger">
                                                    <button
                                                        class="cursor-pointer rounded-md border border-neutral-300 p-2 transition duration-150 ease-in-out hover:bg-neutral-100">
                                                        <x-icon name="ellipsis-horizontal" class="w-6 h-6" />
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                                        <x-icon name="pencil" class="w-5 h-5" />
                                                        Editar
                                                    </a>
                                                    <button type="submit" form="delete-form-{{ $transaction->id }}"
                                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                                        <x-icon name="trash" class="w-5 h-5" />
                                                        Excluir
                                                    </button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">
                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-neutral-200 bg-red-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <x-icon name="arrow-trending-down" class="w-5 h-5 text-red-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-800">Despesas</h3>
                                    <p class="text-sm text-neutral-600">0 transações</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-neutral-500 text-center py-4">Nenhuma despesa registrada neste dia.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- Estado Vazio --}}
        <x-card class="mt-8">
            <x-empty-state icon="calendar-days" message="Nenhuma atividade financeira registrada para este dia."
                actionText="Criar nova transação" :actionRoute="route('transactions.create')" />
        </x-card>
    @endif
</x-layouts.app>
