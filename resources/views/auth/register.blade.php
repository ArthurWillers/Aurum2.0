<x-layouts.guest>
    <x-auth-header title="Criar uma conta" description="Digite seus dados abaixo para criar sua conta" />

    <x-auth-session-status :status="session('status')" />

    <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Nome --}}
        <x-form-input label="Nome" type="text" name="name" :value="old('name')" placeholder="Seu nome completo"
            required autofocus />

        {{-- Endereço de email --}}
        <x-form-input label="Endereço de Email" type="email" name="email" :value="old('email')"
            placeholder="email@exemplo.com" required />

        {{-- Senha --}}
        <x-form-input label="Senha" type="password" name="password" placeholder="Sua senha" required viewable />

        {{-- Confirmar Senha --}}
        <x-form-input label="Confirmar Senha" type="password" name="password_confirmation"
            placeholder="Confirme sua senha" required viewable />

        {{-- Botão criar conta --}}
        <x-button type="submit" class="w-full">
            {{-- Ícone de registro --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>

            Criar Conta
        </x-button>

        {{-- Link para login --}}
        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>Já tem uma conta?</span>
            <x-link href="{{ route('login') }}">
                Fazer Login
            </x-link>
        </div>
    </form>

</x-layouts.guest>
