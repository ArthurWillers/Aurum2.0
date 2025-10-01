<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="mb-6">
        <h2 class="text-2xl font-medium mb-2">
            Configurações
        </h2>
        <p class="mt-1 text-base text-neutral-600">
            Gerencie seu perfil e configurações da conta.
        </p>
    </div>

    <div class="max-w-2xl mx-auto space-y-12">

        <hr class="border-neutral-200" />

        {{-- Informações do Perfil --}}
        <section>
            <header>
                <h3 class="text-lg font-medium">
                    Informações do Perfil
                </h3>
                <p class="mt-1 text-sm text-neutral-600">
                    Atualize as informações de perfil da sua conta.
                </p>
            </header>

            <form method="post" action="{{ route('user-profile-information.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div class="space-y-3">
                    <x-form-input name="name" type="text" label="Nome" class="w-full" :value="old('name', auth()->user()->name)"
                        required />

                    <x-form-input name="email" type="email" label="Email" class="w-full" :value="old('email', auth()->user()->email)"
                        required />
                </div>

                <div class="flex items-center gap-4">
                    <x-button type="submit">Salvar</x-button>
                    @if (session('status') === 'profile-information-updated')
                        <p class="text-sm text-neutral-600">Salvo.</p>
                    @endif
                </div>
            </form>
        </section>

    </div>
</x-layouts.app>
