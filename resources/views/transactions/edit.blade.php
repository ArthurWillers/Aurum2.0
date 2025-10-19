<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Editar Transação" :action="route($transaction->type === 'income' ? 'incomes.index' : 'expenses.index')" actionText="Voltar" />

    <x-card>

        {{-- Aviso para transações parceladas/recorrentes --}}
        @if ($transaction->total_installments || $transaction->transaction_group_uuid)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-md mb-6">
                <div class="flex">
                    <div class="py-1 self-center">
                        <x-icon name="information-circle" class="w-7 h-7 text-yellow-500 mr-4" />
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

            <div class="space-y-6 mb-9"
                x-data='{
                type: @json(old('type', $transaction->type)),
                categories: @json($categories ?? []),
                getFilteredCategories() {
                    return this.categories.filter(cat => cat.type === this.type);
                }
            }'>
                {{-- Receita ou Despesa --}}
                <fieldset class="mb-6">
                    <legend class="text-sm font-semibold text-neutral-700 mb-4">
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
                                <x-icon name="arrow-trending-up" class="w-6 h-6" />
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
                                <x-icon name="arrow-trending-down" class="w-6 h-6" />
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
                    <x-icon name="calendar-days" class="w-5 h-5 text-blue-500" />

                    <span class="text-xs sm:text-sm text-neutral-700 font-semibold">Criado
                        em:</span>
                    <span
                        class="text-xs sm:text-sm font-mono text-neutral-600">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex items-center gap-2 bg-neutral-100 rounded-lg px-4 py-2 shadow-sm">
                    <x-icon name="clock" class="w-5 h-5 text-green-500" />
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
    </x-card>
</x-layouts.app>
