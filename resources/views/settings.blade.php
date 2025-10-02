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

        <hr class="border-neutral-200" />

        {{-- Deletar Conta --}}
        <section>
            <header>
                <h3 class="text-lg font-medium text-red-600">
                    Deletar Conta
                </h3>
                <p class="mt-1 text-sm text-neutral-600">
                    Deletar permanentemente sua conta.
                </p>
            </header>

            <div class="mt-6 space-y-4">
                <p class="text-sm text-neutral-700">
                    Uma vez que sua conta for deletada, todos os seus recursos e dados serão permanentemente deletados.
                    Antes de deletar sua conta, por favor faça o download de quaisquer dados ou informações que você
                    deseja reter.
                </p>

                {{-- Mensagem de sucesso após confirmação de senha --}}
                @if ($isRecentlyConfirmed)
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg" x-data="{
                        timeRemaining: {{ $timeRemaining }},
                        timeText: '',
                        updateTime() {
                            if (this.timeRemaining > 0) {
                                this.timeRemaining--;
                                const minutes = Math.floor(this.timeRemaining / 60);
                                const seconds = this.timeRemaining % 60;
                    
                                if (minutes > 0) {
                                    this.timeText = minutes + (minutes > 1 ? ' minutos' : ' minuto');
                                    if (seconds > 0) {
                                        this.timeText += ' e ' + seconds + (seconds > 1 ? ' segundos' : ' segundo');
                                    }
                                } else {
                                    this.timeText = seconds + (seconds > 1 ? ' segundos' : ' segundo');
                                }
                            } else {
                                window.location.reload();
                            }
                        }
                    }"
                        x-init="updateTime();
                        setInterval(() => updateTime(), 1000)">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5 text-green-600 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <div class="text-sm text-green-800">
                                <p>
                                    <strong>Senha confirmada!</strong> Agora você pode clicar em "Deletar Conta" para
                                    prosseguir.
                                </p>
                                <p class="mt-1 text-green-700">
                                    Esta confirmação expira em aproximadamente
                                    <strong x-text="timeText"></strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Aviso sobre confirmação de senha --}}
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5 text-amber-600 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <p class="text-sm text-amber-800">
                                <strong>Atenção:</strong> Por segurança, você precisará confirmar sua senha antes de
                                deletar
                                sua conta.
                            </p>
                        </div>
                    </div>
                @endif

                <form method="post" action="{{ route('account.destroy') }}"
                    @if ($isRecentlyConfirmed) onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.');" @endif>
                    @csrf
                    @method('delete')
                    <x-button type="submit" color="red">
                        Deletar Conta
                    </x-button>
                </form>
            </div>
        </section>

    </div>
</x-layouts.app>
