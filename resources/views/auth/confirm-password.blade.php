<x-layouts.guest>
    <x-auth-header title="Confirme sua senha"
        description="Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar." />

    <x-auth-session-status :status="session('status')" />

    <form action="{{ route('password.confirm.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Senha --}}
        <x-form-input label="Senha" type="password" name="password" placeholder="Sua senha" required autofocus viewable />

        {{-- Botão de confirmar --}}
        <x-button type="submit" class="w-full">
            {{-- Ícone de lock --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>

            Confirmar
        </x-button>

        {{-- Link voltar --}}
        <div class="text-center">
            <a href="{{ route('settings') }}"
                class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Voltar para configurações
            </a>
        </div>
    </form>
</x-layouts.guest>
