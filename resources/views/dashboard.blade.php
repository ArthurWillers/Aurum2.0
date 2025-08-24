<x-layouts.app>
    <h1>dashboard temporária</h1>

    {{-- Formulário de Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
    
        <x-button type="submit" color="light">
            Sair
        </x-button>
    </form>
</x-layouts.app>