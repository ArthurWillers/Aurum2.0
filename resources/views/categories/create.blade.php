<x-layouts.app>
    {{-- Cabeçalho --}}
    <x-page-header title="Nova Categoria" :action="route('categories.index')" actionText="Voltar" />

    <x-card>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
            @csrf
            <x-form-input label="Nome" name="name" id="name" placeholder="Ex: Salário, Supermercado, Lazer"
                :value="old('name')" autofocus />

            <fieldset class="mb-9">
                <legend class="text-sm font-semibold text-neutral-700 mb-2">
                    Tipo
                </legend>
                <div class="space-y-3">
                    <x-form-radio name="type" value="income" label="Receita" :checked="old('type') === 'income'" />
                    <x-form-radio name="type" value="expense" label="Despesa" :checked="old('type') === 'expense'" />
                </div>
                <x-form-error name="type" />
            </fieldset>

            <div class="flex items-center justify-between">
                <x-button href="{{ route('categories.index') }}" class="text-base!" color="outline">
                    Cancelar
                </x-button>
                <x-button type="submit" class="text-base!">
                    Salvar
                </x-button>
            </div>
    </x-card>

</x-layouts.app>
