<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Nova Transação" :action="route($backRoute)" actionText="Voltar" />

    <x-card>
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Campos ocultos para os tipos selecionados --}}
            <input type="hidden" name="transaction_type" x-model="transactionType">
            <input type="hidden" name="type" x-model="type">

            <div class="space-y-6 mb-9"
                x-data='{
                type: @json($type ?? 'expense'),
                transactionType: "single",
                categories: @json($categories),
                getFilteredCategories() {
                    return this.categories.filter(cat => cat.type === this.type);
                }
            }'>

                {{-- Tipo de Transação --}}
                <fieldset class="mb-6">
                    <legend class="text-sm font-semibold text-neutral-700 mb-4">
                        Tipo de Movimento
                    </legend>
                    <div class="flex gap-2 items-center bg-neutral-200 rounded-lg p-1">
                        <label class="flex-1">
                            <input type="radio" name="transaction_type" value="single" x-model="transactionType"
                                class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:shadow-sm
                            peer-checked:text-neutral-900
                            text-neutral-700">
                                <x-icon name="currency-dollar" class="w-6 h-6" />

                                <span class="text-xs font-medium">Única</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="transaction_type" value="recurring" x-model="transactionType"
                                class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:shadow-sm
                            peer-checked:text-neutral-900
                            text-neutral-700">
                                <x-icon name="arrow-path" class="w-6 h-6" />

                                <span class="text-xs font-medium">Recorrente</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="transaction_type" value="installment" x-model="transactionType"
                                class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:shadow-sm
                            peer-checked:text-neutral-900
                            text-neutral-700">
                                <x-icon name="calendar-days" class="w-6 h-6" />

                                <span class="text-xs font-medium">Parcelada</span>
                            </div>
                        </label>
                    </div>
                </fieldset>

                {{-- Receita ou Despesa --}}
                <fieldset class="mb-6">
                    <legend class="text-sm font-semibold text-neutral-700 mb-3">
                        Receita ou Despesa
                    </legend>
                    <div class="flex gap-2 items-center bg-neutral-200 rounded-lg p-1">
                        <label class="flex-1">
                            <input type="radio" name="type" value="income" x-model="type" class="peer sr-only" />
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
                            <input type="radio" name="type" value="expense" x-model="type" class="peer sr-only" />
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

                {{-- Descrição --}}
                <x-form-input label="Descrição" name="description" placeholder="Ex: Compras no Supermercado"
                    :value="old('description')" autofocus />

                {{-- Valor para transação única e recorrente --}}
                <div x-show="transactionType === 'single' || transactionType === 'recurring'" x-transition>
                    <x-form-input label="Valor (R$)" name="amount" placeholder="150,00" numeric :value="old('amount')" />
                </div>

                {{-- Valor total para transação parcelada --}}
                <div x-show="transactionType === 'installment'" x-transition>
                    <x-form-input label="Valor Total (R$)" name="total_amount" placeholder="1200,00" numeric
                        :value="old('total_amount')" />
                </div>

                {{-- Data - Para transação única --}}
                <div x-show="transactionType === 'single'" x-transition>
                    <x-form-input type="date" name="single_date" label="Data da Transação"
                        value="{{ old('single_date', session('selected_month') ? (session('selected_month') == now()->format('Y-m') ? now()->format('Y-m-d') : session('selected_month') . '-01') : now()->format('Y-m-d')) }}" />
                </div>

                {{-- Data - Para transação recorrente --}}
                <div x-show="transactionType === 'recurring'" x-transition>
                    <x-form-input type="date" name="recurring_date" label="Data de Início"
                        value="{{ old('recurring_date', session('selected_month') ? (session('selected_month') == now()->format('Y-m') ? now()->format('Y-m-d') : session('selected_month') . '-01') : now()->format('Y-m-d')) }}" />
                </div>

                {{-- Data - Para transação parcelada --}}
                <div x-show="transactionType === 'installment'" x-transition>
                    <x-form-input type="date" name="installment_date" label="Data da Primeira Parcela"
                        value="{{ old('installment_date', session('selected_month') ? (session('selected_month') == now()->format('Y-m') ? now()->format('Y-m-d') : session('selected_month') . '-01') : now()->format('Y-m-d')) }}" />
                </div>

                {{-- Categoria --}}
                <x-form-select label="Categoria" name="category_id">
                    <option value="" disabled selected>Selecione uma categoria</option>

                    <template x-for="category in getFilteredCategories()" :key="category.id">
                        <option :value="category.id" :selected="category.id == '{{ old('category_id') }}'"
                            x-text="category.name">
                        </option>
                    </template>
                </x-form-select>

                {{-- Campos condicionais para transação recorrente --}}
                <div x-show="transactionType === 'recurring'" x-transition class="space-y-4">
                    <x-form-input type="number" name="recurring_months" label="Duração (meses)" placeholder="12"
                        min="1" :value="old('recurring_months')" />
                </div>

                {{-- Campos condicionais para transação parcelada --}}
                <div x-show="transactionType === 'installment'" x-transition class="space-y-4">
                    <x-form-input type="number" name="installments" label="Número de Parcelas" placeholder="10"
                        min="2" max="60" :value="old('installments')" />
                </div>

                {{-- Informações contextuais --}}
                <div x-show="transactionType !== 'single'" x-transition class="p-4 rounded-lg border-l-4"
                    :class="{
                        'bg-blue-50 border-blue-400': transactionType === 'recurring',
                        'bg-green-50 border-green-400': transactionType === 'installment'
                    }">
                    <div class="flex">
                        <div class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0">
                            <x-icon name="information-circle" class="w-5 h-5 text-blue-600"
                                x-show="transactionType === 'recurring'" />
                            <x-icon name="information-circle" class="w-5 h-5 text-green-600"
                                x-show="transactionType === 'installment'" />
                        </div>
                        <div class="text-sm">
                            <p x-show="transactionType === 'recurring'" class="text-blue-800">
                                <strong>Transação Recorrente:</strong> Será criada uma transação para cada mês durante o
                                período especificado.
                            </p>
                            <p x-show="transactionType === 'installment'" class="text-green-800">
                                <strong>Transação Parcelada:</strong> O valor total será dividido automaticamente pelo
                                número de parcelas.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="flex items-center justify-between">
                <x-button href="{{ route($backRoute) }}" class="text-base!" color="outline">
                    Cancelar
                </x-button>
                <x-button type="submit" class="text-base!">
                    Salvar
                </x-button>
            </div>
        </form>
    </x-card>

</x-layouts.app>
