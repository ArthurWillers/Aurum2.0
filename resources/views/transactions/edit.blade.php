<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Editar Transação</h2>
        <x-button href="{{ route($transaction->type === 'income' ? 'incomes.index' : 'expenses.index') }}">
            Voltar
        </x-button>
    </div>

    <div
        class="bg-white rounded-lg shadow-lg p-6 w-full border border-neutral-200">

        {{-- Aviso para transações parceladas/recorrentes --}}
        @if ($transaction->total_installments || $transaction->transaction_group_uuid)
            <div
                class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-6">
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

            <div class="space-y-4 mb-9"
                x-data='{
                type: @json(old('type', $transaction->type)),
                categories: @json($categories ?? []),
                getFilteredCategories() {
                    return this.categories.filter(cat => cat.type === this.type);
                }
            }'>
                {{-- Receita ou Despesa --}}
                <fieldset class="mb-6">
                    <legend class="text-sm font-semibold text-neutral-700 mb-3">
                        Receita ou Despesa
                    </legend>
                    <div class="flex gap-2 items-center bg-neutral-200 rounded-lg p-1">
                        <label class="flex-1">
                            <input type="radio" name="type" value="income" x-model="type" class="peer sr-only"
                                {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }} />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:shadow-sm
                            peer-checked:text-green-600
                            text-green-600 hover:text-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                                <span class="text-sm font-medium">Receita</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="type" value="expense" x-model="type" class="peer sr-only"
                                {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }} />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:shadow-sm
                            peer-checked:text-red-600
                            text-red-600 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.50-5.511-3.181" />
                                </svg>
                                <span class="text-sm font-medium">Despesa</span>
                            </div>
                        </label>
                    </div>
                    <x-form-error name="type" />
                </fieldset>

                <x-form-input label="Descrição" name="description" placeholder="{{ $transaction->description }}"
                    :value="old('description', $transaction->description)" autofocus />

                <x-form-input label="Valor (R$)" name="amount" placeholder="{{ $transaction->amount }}" numeric
                    :value="old('amount', $transaction->amount)" />

                <x-form-input type="date" name="date" label="Data da Transação"
                    value="{{ old('date', $transaction->date->format('Y-m-d')) }}" />

                {{-- Categoria --}}
                <x-form-select label="Categoria" name="category_id">
                    <option value="" disabled selected>Selecione uma categoria</option>

                    <template x-for="category in getFilteredCategories()" :key="category.id">
                        <option :value="category.id"
                            :selected="category.id == '{{ old('category_id', $transaction->category_id) }}'"
                            x-text="category.name">
                        </option>
                    </template>
                </x-form-select>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-center justify-center my-6">
                <div class="flex items-center gap-2 bg-neutral-100 rounded-lg px-4 py-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>

                    <span class="text-xs sm:text-sm text-neutral-700 font-semibold">Criado
                        em:</span>
                    <span
                        class="text-xs sm:text-sm font-mono text-neutral-600">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex items-center gap-2 bg-neutral-100 rounded-lg px-4 py-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-xs sm:text-sm text-neutral-700 font-semibold">Atualizado
                        em:</span>
                    <span
                        class="text-xs sm:text-sm font-mono text-neutral-600">{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>

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
