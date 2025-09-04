<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Nova Transação</h2>
        <x-button href="{{ route($backRoute) }}">
            Voltar
        </x-button>
    </div>

    <div
        class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6 w-full border border-neutral-200 dark:border-neutral-700">
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Campos ocultos para os tipos selecionados --}}
            <input type="hidden" name="transaction_type" x-model="transactionType">
            <input type="hidden" name="type" x-model="type">

            <div class="space-y-4 mb-9"
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
                    <legend class="text-sm font-semibold text-neutral-700 dark:text-neutral-100 mb-3">
                        Tipo de Transação
                    </legend>
                    <div class="flex gap-1 items-center bg-neutral-200 dark:bg-neutral-700 rounded-lg p-1">
                        <label class="flex-1">
                            <input type="radio" name="transaction_type" value="single" x-model="transactionType"
                                class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                            peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                            text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>

                                <span class="text-xs font-medium">Única</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="transaction_type" value="recurring" x-model="transactionType"
                                class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                            peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                            text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>

                                <span class="text-xs font-medium">Recorrente</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="transaction_type" value="installment" x-model="transactionType"
                                class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                            peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                            text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>

                                <span class="text-xs font-medium">Parcelada</span>
                            </div>
                        </label>
                    </div>
                </fieldset>

                {{-- Receita ou Despesa --}}
                <fieldset class="mb-6">
                    <legend class="text-sm font-semibold text-neutral-700 dark:text-neutral-100 mb-3">
                        Receita ou Despesa
                    </legend>
                    <div class="flex gap-2 items-center bg-neutral-200 dark:bg-neutral-700 rounded-lg p-1">
                        <label class="flex-1">
                            <input type="radio" name="type" value="income" x-model="type" class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                            peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                            text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                                <span class="text-sm font-medium">Receita</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="type" value="expense" x-model="type" class="peer sr-only" />
                            <div
                                class="flex flex-col items-center justify-center py-3 rounded-md transition-colors cursor-pointer
                            peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                            peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                            text-neutral-700 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                                </svg>
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
                    <x-form-input type="date" name="date" label="Data da Transação"
                        value="{{ old('date', session('selected_month') ? (session('selected_month') == now()->format('Y-m') ? now()->format('Y-m-d') : session('selected_month') . '-01') : now()->format('Y-m-d')) }}" />
                </div>

                {{-- Data - Para transação recorrente --}}
                <div x-show="transactionType === 'recurring'" x-transition>
                    <x-form-input type="date" name="date" label="Data de Início"
                        value="{{ old('date', session('selected_month') ? (session('selected_month') == now()->format('Y-m') ? now()->format('Y-m-d') : session('selected_month') . '-01') : now()->format('Y-m-d')) }}" />
                </div>

                {{-- Data - Para transação parcelada --}}
                <div x-show="transactionType === 'installment'" x-transition>
                    <x-form-input type="date" name="date" label="Data da Primeira Parcela"
                        value="{{ old('date', session('selected_month') ? (session('selected_month') == now()->format('Y-m') ? now()->format('Y-m-d') : session('selected_month') . '-01') : now()->format('Y-m-d')) }}" />
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
                        'bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-600': transactionType === 'recurring',
                        'bg-green-50 dark:bg-green-900/20 border-green-400 dark:border-green-600': transactionType === 'installment'
                    }">
                    <div class="flex">
                        <svg class="w-5 h-5 mt-0.5 mr-3"
                            :class="{
                                'text-blue-600 dark:text-blue-400': transactionType === 'recurring',
                                'text-green-600 dark:text-green-400': transactionType === 'installment'
                            }"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm">
                            <p x-show="transactionType === 'recurring'" class="text-blue-800 dark:text-blue-200">
                                <strong>Transação Recorrente:</strong> Será criada uma transação para cada mês durante o
                                período especificado.
                            </p>
                            <p x-show="transactionType === 'installment'" class="text-green-800 dark:text-green-200">
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
    </div>

</x-layouts.app>
