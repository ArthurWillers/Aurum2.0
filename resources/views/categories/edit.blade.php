<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Editar Categoria</h2>
        <x-button href="{{ route('categories.index') }}">
            Voltar
        </x-button>
    </div>

    <div
        class="bg-white rounded-lg shadow-lg p-6 w-full border border-neutral-200">

        <div
            class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4">
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>

                    <span class="text-xs sm:text-sm text-neutral-700 font-semibold">Criado
                        em:</span>
                    <span
                        class="text-xs sm:text-sm font-mono text-neutral-600">{{ $category->created_at->format('d/m/Y H:i:s') }}</span>
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
    </div>

</x-layouts.app>
