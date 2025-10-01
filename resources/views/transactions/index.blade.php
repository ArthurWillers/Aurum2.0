<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">{{ $title }}</h2>

        <x-button href="{{ route('transactions.create', $type) }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            {{ $type === 'income' ? 'Nova Receita' : 'Nova Despesa' }}
        </x-button>
    </div>

    {{-- Filtros --}}
    <div class="mb-4">
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

            {{-- Botões de ação - Mobile e Desktop --}}
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 sm:items-center">
                @if (request('category_id'))
                    <a href="{{ $type === 'income' ? route('incomes.index') : route('expenses.index') }}"
                        class="inline-flex items-center justify-center px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 sm:mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpar filtro
                    </a>
                @endif

                {{-- Botão para alternar paginação --}}
                <div class="sm:mb-1">
                    @if (request('show_all'))
                        <a href="{{ ($type === 'income' ? route('incomes.index') : route('expenses.index')) . (request('category_id') ? '?category_id=' . request('category_id') : '') }}"
                            class="inline-flex items-center justify-center w-full sm:w-auto px-3 py-2 text-sm bg-neutral-100 text-neutral-700 rounded-md hover:bg-neutral-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            Paginar
                        </a>
                    @else
                        <a href="{{ ($type === 'income' ? route('incomes.index') : route('expenses.index')) . '?show_all=1' . (request('category_id') ? '&category_id=' . request('category_id') : '') }}"
                            class="inline-flex items-center justify-center w-full sm:w-auto px-3 py-2 text-sm bg-neutral-100 text-neutral-700 rounded-md hover:bg-neutral-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                            Mostrar Todas
                        </a>
                    @endif
                </div>
            </div>

        </form>
    </div>

    <div
        class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">

        {{-- Header - Desktop --}}
        <div
            class="hidden sm:grid sm:grid-cols-[2fr_1fr_1fr_1fr_0.5fr] border-b border-neutral-200">
            <div class="px-4 lg:px-6 py-3 text-left">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Descrição</span>
            </div>
            <div class="px-4 lg:px-6 py-3 text-left">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Categoria</span>
            </div>
            <div class="px-4 lg:px-6 py-3 text-left">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Data</span>
            </div>
            <div class="px-4 lg:px-6 py-3 text-left">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Valor</span>
            </div>
            <div class="px-4 lg:px-6 py-3 text-end">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Ações</span>
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
                            <span class="text-sm text-neutral-600">
                                {{ $transaction->date->format('d/m/Y') }}
                            </span>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                        Editar
                                    </a>

                                    <button type="submit" form="delete-form-{{ $transaction->id }}"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
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
                                    <span class="text-sm text-neutral-600">
                                        {{ $transaction->date->format('d/m/Y') }}
                                    </span>
                                </div>
                                <h3
                                    class="text-base font-semibold text-neutral-900 leading-tight mb-1">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <a href="{{ route('transactions.edit', $transaction) }}"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                            Editar
                                        </a>

                                        <button type="submit" form="delete-form-{{ $transaction->id }}"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Excluir
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 px-4 text-center">
                    <div class="text-neutral-600">
                        @if (request('category_id'))
                            @php
                                $selectedCategory = $categories->firstWhere('id', request('category_id'));
                            @endphp
                            <p class="font-medium">Não há {{ $type === 'income' ? 'receitas' : 'despesas' }}
                                na categoria "{{ $selectedCategory?->name }}" neste mês</p>
                        @else
                            <p class="font-medium">Você ainda não criou nenhuma
                                {{ $type === 'income' ? 'receita' : 'despesa' }} neste mês</p>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @if (!request('show_all') && method_exists($transactions, 'hasPages') && $transactions->hasPages())
        <div class="mt-1">
            {{ $transactions->links() }}
        </div>
    @endif

</x-layouts.app>
