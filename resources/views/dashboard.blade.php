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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-8">
        {{-- Card Receitas do Mês --}}
        <a href="{{ route('incomes.index') }}"
            class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6 border border-neutral-200 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Receitas do Mês</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                        R$ {{ number_format($incomes, 2, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-green-600 dark:text-green-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                </div>
            </div>
        </a>

        {{-- Card Despesas do Mês --}}
        <a href="{{ route('expenses.index') }}"
            class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6 border border-neutral-200 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-200 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Despesas do Mês</p>
                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                        R$ {{ number_format($expenses, 2, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-red-600 dark:text-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                    </svg>
                </div>
            </div>
        </a>

        {{-- Card Saldo do Mês --}}
        <div
            class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Saldo do Mês</p>
                    <p
                        class="text-2xl font-semibold {{ $incomes - $expenses >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        R$ {{ number_format($incomes - $expenses, 2, ',', '.') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 {{ $incomes - $expenses >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6 {{ $incomes - $expenses >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
