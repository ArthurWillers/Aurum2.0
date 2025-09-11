<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Editar Transação</h2>
        <x-button href="{{ route($transaction->type === 'income' ? 'incomes.index' : 'expenses.index') }}">
            Voltar
        </x-button>
    </div>

</x-layouts.app>
