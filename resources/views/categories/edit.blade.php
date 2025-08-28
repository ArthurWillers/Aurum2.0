<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Editar Categoria</h2>
        <x-button href="{{ route('categories.index') }}">
            Voltar
        </x-button>
    </div>

    <div
        class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6 w-full border border-neutral-200 dark:border-neutral-700">

        <div
            class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-600 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded mb-4">
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
                    <p class="text-sm">Ao alterar o nome ou tipo desta categoria, todas as transações relacionadas serão
                        atualizadas automaticamente.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4"
            onsubmit="return confirm('Tem certeza que deseja salvar as alterações? Todas as transações relacionadas serão atualizadas.')">
            @csrf
            @method('PUT')

            <x-form-input label="Nome" name="name" id="name" placeholder="{{ $category->name }}"
                value="{{ old('name', $category->name) }}" />

            <fieldset class="mb-9">
                <legend class="text-sm font-semibold text-neutral-700 dark:text-neutral-100 mb-2">
                    Tipo
                </legend>
                <div class="space-y-3">
                    <x-form-radio name="type" value="income" label="Receita" :checked="old('type', $category->type) === 'income'" />
                    <x-form-radio name="type" value="expense" label="Despesa" :checked="old('type', $category->type) === 'expense'" />
                </div>
                <x-form-error name="type" />
            </fieldset>

            <div class="flex items-center justify-between">
                <x-button href="{{ route('categories.index') }}" class="text-base!" color="outline">
                    Cancelar
                </x-button>
                <x-button type="submit" class="text-base!">
                    Salvar Alterações
                </x-button>
            </div>
        </form>
    </div>

</x-layouts.app>
