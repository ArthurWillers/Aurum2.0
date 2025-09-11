<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Editar Transação</h2>
        <x-button href="{{ route($transaction->type === 'income' ? 'incomes.index' : 'expenses.index') }}">
            Voltar
        </x-button>
    </div>

    <div
        class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6 w-full border border-neutral-200 dark:border-neutral-700">

        {{-- Aviso para transações parceladas/recorrentes --}}
        @if ($transaction->total_installments || $transaction->transaction_group_uuid)
            <div
                class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-600 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded mb-6">
                <div class="flex">
                    <div class="py-1 self-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7 text-yellow-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Atenção!</p>
                        <p class="text-sm">Esta é uma transação
                            {{ $transaction->total_installments ? 'parcelada' : 'recorrente' }}.
                            Ao alterar os dados, apenas esta transação específica será modificada.</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Botões --}}
            <div class="flex items-center justify-between">
                <x-button href="{{ route($transaction->type === 'income' ? 'incomes.index' : 'expenses.index') }}"
                    class="text-base!" color="outline">
                    Cancelar
                </x-button>
                <x-button type="submit" class="text-base!">
                    Salvar Alterações
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
