<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Categorias</h2>
        <x-button href="{{ route('categories.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nova Categoria
        </x-button>
    </div>

    <div
        class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">

        {{-- Header - Desktop --}}
        <div class="hidden sm:grid sm:grid-cols-[2fr_1fr_1fr] border-b border-neutral-200">
            <div class="px-4 lg:px-6 py-3 text-left">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Nome</span>
            </div>
            <div class="px-4 lg:px-6 py-3 text-left">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Tipo</span>
            </div>
            <div class="px-4 lg:px-6 py-3 text-end">
                <span
                    class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Ações</span>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="divide-y divide-neutral-200">
            @forelse ($categories as $category)
                <div class="relative">
                    <form method="POST" id="delete-form-{{ $category->id }}"
                        action="{{ route('categories.destroy', $category) }}"
                        onsubmit="return confirm('Você tem certeza que deseja excluir esta categoria?');">
                        @csrf
                        @method('DELETE')
                    </form>
                    {{-- Versão Desktop/Tablet --}}
                    <div class="hidden sm:grid sm:grid-cols-[2fr_1fr_1fr] items-center">
                        <div class="px-4 lg:px-6 py-4">
                            <span class="text-sm font-medium">{{ $category->name }}</span>
                        </div>
                        <div class="px-4 lg:px-6 py-4">
                            <span
                                class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-md
                                {{ $category->type === 'income'
                                    ? 'text-green-700 bg-green-400/20'
                                    : 'text-red-700 bg-red-400/20' }}">
                                {{ $category->type === 'income' ? 'Receita' : 'Despesa' }}
                            </span>
                        </div>
                        <div class="px-4 lg:px-6 py-4 flex justify-end">
                            <x-dropdown position="bottom-end" accent>
                                <x-slot name="trigger">
                                    <button
                                        class="cursor-pointer rounded-md border border-neutral-300 p-2 transition duration-150 ease-in-out hover:bg-neutral-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>

                                        Editar
                                    </a>

                                    <button type="submit"
                                        form="delete-form-{{ $category->id }}"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                        Excluir
                                    </button>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>

                    {{-- Versão Mobile --}}
                    <div class="sm:hidden p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md
                                        {{ $category->type === 'income'
                                            ? 'text-green-700 bg-green-400/20'
                                            : 'text-red-700 bg-red-400/20' }}">
                                        {{ $category->type === 'income' ? 'Receita' : 'Despesa' }}
                                    </span>
                                </div>
                                <h3
                                    class="text-base font-semibold text-neutral-900 leading-tight">
                                    {{ $category->name }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <x-dropdown position="bottom-end" accent>
                                    <x-slot name="trigger">
                                        <button
                                            class="cursor-pointer rounded-md border border-neutral-300 p-2 transition duration-150 ease-in-out hover:bg-neutral-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>

                                            Editar
                                        </a>

                                        <button type="submit"
                                            form="delete-form-{{ $category->id }}"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                            Excluir
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 px-4 text-center">
                    <div class="text-neutral-600">
                        <p class="font-medium">Você ainda não criou nenhuma categoria</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-2">
        {{ $categories->links() }}
    </div>

</x-layouts.app>
