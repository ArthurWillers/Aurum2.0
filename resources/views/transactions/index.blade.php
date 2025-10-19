<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header :title="$title" :action="route('transactions.create', $type)" :actionText="$type === 'income' ? 'Nova Receita' : 'Nova Despesa'" icon="plus" />

    {{-- Filtros --}}
    <div class="mb-6">
        <form method="GET" action="{{ $type === 'income' ? route('incomes.index') : route('expenses.index') }}"
            class="flex flex-col sm:flex-row gap-4 sm:items-end">
            <div class="flex-1 w-full">
                <x-form-select label="Filtrar por Categoria" name="category_id" onchange="this.form.submit()">
                    <option value="">Todas as categorias</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-form-select>
            </div>

            {{-- Botão de ação - Limpar filtro --}}
            @if (request('category_id'))
                <div class="sm:mb-1">
                    <a href="{{ $type === 'income' ? route('incomes.index') : route('expenses.index') }}"
                        class="inline-flex items-center justify-center px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900">
                        <x-icon name="x-mark" class="w-4 h-4 mr-1" />
                        Limpar filtro
                    </a>
                </div>
            @endif

        </form>
    </div>

    <div class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white lg:mb-8">

        {{-- Header - Desktop --}}
        <div class="hidden sm:grid sm:grid-cols-[2fr_1fr_1fr_1fr_0.5fr] border-b border-neutral-200">
            <div class="px-4 lg:px-6 py-4 text-left">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Descrição</span>
            </div>
            <div class="px-4 lg:px-6 py-4 text-left">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Categoria</span>
            </div>
            <div class="px-4 lg:px-6 py-4 text-left">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Data</span>
            </div>
            <div class="px-4 lg:px-6 py-4 text-left">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Valor</span>
            </div>
            <div class="px-4 lg:px-6 py-4 text-end">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Ações</span>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="divide-y divide-neutral-200">
            @forelse ($transactions as $transaction)
                <div class="relative">
                    <form method="POST" id="delete-form-{{ $transaction->id }}"
                        action="{{ route('transactions.destroy', $transaction) }}"
                        onsubmit="return confirm('Você tem certeza que deseja excluir esta transação?');">
                        @csrf
                        @method('DELETE')
                    </form>
                    {{-- Versão Desktop/Tablet --}}
                    <div class="hidden sm:grid sm:grid-cols-[2fr_1fr_1fr_1fr_0.5fr] items-center">
                        <div class="px-4 lg:px-6 py-4">
                            <span class="text-sm font-medium">{{ $transaction->description }}</span>
                            @if ($transaction->total_installments)
                                <span class="text-xs text-neutral-500">
                                    ({{ $transaction->installment_number }}/{{ $transaction->total_installments }})
                                </span>
                            @endif
                        </div>
                        <div class="px-4 lg:px-6 py-4">
                            <span class="text-sm text-neutral-600">
                                {{ $transaction->category->name ?? 'Sem categoria' }}
                            </span>
                        </div>
                        <div class="px-4 lg:px-6 py-4">
                            <a href="{{ route('daily-summary', ['date' => $transaction->date->format('Y-m-d')]) }}"
                                class="text-sm text-neutral-600 hover:text-amber-600 hover:underline transition-colors">
                                {{ $transaction->date->format('d/m/Y') }}
                            </a>
                        </div>
                        <div class="px-4 lg:px-6 py-4">
                            <span
                                class="text-sm font-medium
                                {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}R$
                                {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="px-4 lg:px-6 py-4 flex justify-end">
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

                    {{-- Versão Mobile --}}
                    <div class="sm:hidden p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <a href="{{ route('daily-summary', ['date' => $transaction->date->format('Y-m-d')]) }}"
                                        class="text-sm text-neutral-600 hover:text-amber-600 hover:underline transition-colors">
                                        {{ $transaction->date->format('d/m/Y') }}
                                    </a>
                                </div>
                                <h3 class="text-base font-semibold text-neutral-900 leading-tight mb-1">
                                    {{ $transaction->description }}
                                    @if ($transaction->total_installments)
                                        <span class="text-sm text-neutral-500 font-normal">
                                            ({{ $transaction->installment_number }}/{{ $transaction->total_installments }})
                                        </span>
                                    @endif
                                </h3>
                                <div class="flex items-center gap-2 text-sm text-neutral-600">
                                    <span>{{ $transaction->category->name ?? 'Sem categoria' }}</span>
                                </div>
                                <div class="mt-2">
                                    <span
                                        class="text-lg font-semibold
                                        {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}R$
                                        {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </span>
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
                </div>
            @empty
                @if (request('category_id'))
                    @php
                        $selectedCategory = $categories->firstWhere('id', request('category_id'));
                    @endphp
                    <x-empty-state
                        message="Não há {{ $type === 'income' ? 'receitas' : 'despesas' }} na categoria '{{ $selectedCategory?->name }}' neste mês" />
                @else
                    <x-empty-state
                        message="Você ainda não criou nenhuma {{ $type === 'income' ? 'receita' : 'despesa' }} neste mês"
                        actionText="Criar {{ $type === 'income' ? 'Receita' : 'Despesa' }}" :actionRoute="route('transactions.create', $type)" />
                @endif
            @endforelse
        </div>
    </div>

</x-layouts.app>
