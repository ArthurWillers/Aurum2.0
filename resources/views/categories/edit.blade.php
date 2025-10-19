<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Editar Categoria" :action="route('categories.index')" actionText="Voltar" />

    <x-card>

        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-md mb-6">
            <div class="flex">
                <div class="py-1 self-center">
                    <x-icon name="information-circle" class="w-7 h-7 text-yellow-500 mr-4" />
                </div>
                <div>
                    <p class="font-bold">Atenção!</p>
                    <p class="text-sm">Ao alterar o nome ou tipo desta categoria, todas as transações relacionadas serão
                        atualizadas automaticamente.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6"
            onsubmit="return confirm('Tem certeza que deseja salvar as alterações? Todas as transações relacionadas serão atualizadas.')">
            @csrf
            @method('PUT')

            <x-form-input label="Nome" name="name" id="name" placeholder="{{ $category->name }}"
                value="{{ old('name', $category->name) }}" />

            <fieldset class="mb-9">
                <legend class="text-sm font-semibold text-neutral-700 mb-2">
                    Tipo
                </legend>
                <div class="space-y-3">
                    <x-form-radio name="type" value="income" label="Receita" :checked="old('type', $category->type) === 'income'" />
                    <x-form-radio name="type" value="expense" label="Despesa" :checked="old('type', $category->type) === 'expense'" />
                </div>
                <x-form-error name="type" />
            </fieldset>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 items-center justify-center my-6">
                <div class="flex items-center gap-2 bg-neutral-100 rounded-lg px-4 py-2 shadow-sm">
                    <x-icon name="calendar-days" class="w-5 h-5 text-blue-500" />

                    <span class="text-xs sm:text-sm text-neutral-700 font-semibold">Criado
                        em:</span>
                    <span
                        class="text-xs sm:text-sm font-mono text-neutral-600">{{ $category->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex items-center gap-2 bg-neutral-100 rounded-lg px-4 py-2 shadow-sm">
                    <x-icon name="clock" class="w-5 h-5 text-green-500" />
                    <span class="text-xs sm:text-sm text-neutral-700 font-semibold">Atualizado
                        em:</span>
                    <span
                        class="text-xs sm:text-sm font-mono text-neutral-600">{{ $category->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <x-button href="{{ route('categories.index') }}" class="text-base!" color="outline">
                    Cancelar
                </x-button>
                <x-button type="submit" class="text-base!">
                    Salvar Alterações
                </x-button>
            </div>
        </form>
    </x-card>

</x-layouts.app>
