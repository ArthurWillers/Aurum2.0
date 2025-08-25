<x-layouts.guest>
    <x-auth-header title="Esqueceu a senha" description="Digite seu email para receber um link de redefinição de senha" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        {{-- Token de Redefinição de Senha --}}
        <input type="hidden" name="token" value="{{ request('token') }}">

        {{-- Endereço de email --}}
        <x-form-input label="Endereço de Email" type="email" name="email" :value="request('email') ?? old('email')"
            placeholder="email@exemplo.com" required readonly />

        {{-- Senha --}}
        <x-form-input label="Nova Senha" type="password" name="password" placeholder="Sua nova senha" required autofocus
            viewable />

        {{-- Confirmar Senha --}}
        <x-form-input label="Confirmar Nova Senha" type="password" name="password_confirmation"
            placeholder="Confirme sua nova senha" required viewable />

        {{-- Botão para redefinir senha --}}
        <x-button type="submit" class="w-full">
            {{-- Ícone de redefinição --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
            </svg>

            Redefinir Senha
        </x-button>

        {{-- Link para login --}}
        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>Lembrou sua senha?</span>
            <x-link href="{{ route('login') }}">
                Fazer Login
            </x-link>
        </div>
    </form>
</x-layouts.guest>
