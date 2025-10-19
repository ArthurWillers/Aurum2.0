<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Categorias" :action="route('categories.create')" actionText="Nova Categoria" icon="plus" />

    <div class="w-full rounded-lg shadow-xl border border-neutral-200 bg-white">

        {{-- Header - Desktop --}}
        <div class="hidden sm:grid sm:grid-cols-[2fr_1fr_1fr] border-b border-neutral-200">
            <div class="px-4 lg:px-6 py-4 text-left">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Nome</span>
            </div>
            <div class="px-4 lg:px-6 py-4 text-left">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Tipo</span>
            </div>
            <div class="px-4 lg:px-6 py-4 text-end">
                <span class="text-xs font-medium text-neutral-600 uppercase tracking-wider">Ações</span>
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
                                {{ $category->type === 'income' ? 'text-green-700 bg-green-400/20' : 'text-red-700 bg-red-400/20' }}">
                                {{ $category->type === 'income' ? 'Receita' : 'Despesa' }}
                            </span>
                        </div>
                        <div class="px-4 lg:px-6 py-4 flex justify-end">
                            <x-dropdown position="bottom-end" accent>
                                <x-slot name="trigger">
                                    <button
                                        class="cursor-pointer rounded-md border border-neutral-300 p-2 transition duration-150 ease-in-out hover:bg-neutral-100">
                                        <x-icon name="ellipsis-horizontal" class="w-6 h-6" />
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                        <x-icon name="pencil" class="w-5 h-5" />

                                        Editar
                                    </a>

                                    <button type="submit" form="delete-form-{{ $category->id }}"
                                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                        <x-icon name="trash" class="w-5 h-5" />

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
                                        {{ $category->type === 'income' ? 'text-green-700 bg-green-400/20' : 'text-red-700 bg-red-400/20' }}">
                                        {{ $category->type === 'income' ? 'Receita' : 'Despesa' }}
                                    </span>
                                </div>
                                <h3 class="text-base font-semibold text-neutral-900 leading-tight">
                                    {{ $category->name }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <x-dropdown position="bottom-end" accent>
                                    <x-slot name="trigger">
                                        <button
                                            class="cursor-pointer rounded-md border border-neutral-300 p-2 transition duration-150 ease-in-out hover:bg-neutral-100">
                                            <x-icon name="ellipsis-horizontal" class="w-6 h-6" />
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200">
                                            <x-icon name="pencil" class="w-5 h-5" />

                                            Editar
                                        </a>

                                        <button type="submit" form="delete-form-{{ $category->id }}"
                                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 cursor-pointer">
                                            <x-icon name="trash" class="w-5 h-5" />

                                            Excluir
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state message="Você ainda não criou nenhuma categoria" actionText="Nova Categoria"
                    :actionRoute="route('categories.create')" />
            @endforelse
        </div>
    </div>

</x-layouts.app>
