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
                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>

            Confirmar
        </x-button>

    </form>

</x-layouts.guest>
