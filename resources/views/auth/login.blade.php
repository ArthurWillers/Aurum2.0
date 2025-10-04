<x-layouts.guest>
    <x-auth-header title="Faça login na sua conta" description="Digite seu email e senha abaixo para fazer login" />

    <x-auth-session-status :status="session('status')" />

    <form action="{{ route('login.store') }}" method="POST" class="space-y-6" x-data="{ loading: false }"
        @submit="loading = true">
        @csrf

        {{-- Endereço de email --}}
        <x-form-input label="Endereço de Email" type="email" name="email" :value="old('email')"
            placeholder="email@exemplo.com" required autofocus />

        <div class="relative">
            {{-- Links esqueceu a senha --}}
            <a href="{{ route('password.request') }}"
                class="absolute right-0 top-0 text-sm font-semibold text-neutral-700
              underline underline-offset-4 decoration-neutral-700/30 hover:decoration-neutral-700
             
              transition-colors duration-300">
                Esqueceu sua senha?
            </a>

            {{-- Senha --}}
            <x-form-input label="Senha" type="password" name="password" placeholder="Sua senha" required viewable />
        </div>

        {{-- Checkbox Lembrar de mim --}}
        <x-form-checkbox name="remember" label="Lembrar de mim" />

        {{-- Botão de login --}}
        <x-button type="submit" class="w-full">
            {{-- Ícone de login --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
            </svg>

            Entrar
        </x-button>

    </form>
    {{-- Link para registrar --}}
    <div class="text-center text-sm text-neutral-600">
        <span>Não tem uma conta?</span>
        <x-link href="{{ route('register') }}">
            Criar Conta
        </x-link>
    </div>

</x-layouts.guest>
