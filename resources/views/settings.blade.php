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

    <div class="max-w-2xl mx-auto space-y-12 pb-5">

        <hr class="border-neutral-200" />

        {{-- Gerenciamento de Dados --}}
        <section>
            <header>
                <h3 class="text-lg font-medium">
                    Gerenciamento de Dados
                </h3>
                <p class="mt-1 text-sm text-neutral-600">
                    Exporte ou importe seus dados de categorias e transações.
                </p>
            </header>

            <div class="mt-6 space-y-8">
                {{-- Exportação de Dados --}}
                <div>
                    <h4 class="text-base font-medium mb-2">
                        Exportar seus dados
                    </h4>
                    <p class="text-sm text-neutral-600 mb-4">
                        Faça o download de todas as suas categorias e transações em formato CSV.
                        Os dados serão exportados em um arquivo compactado (.zip).
                    </p>

                    <x-button href="{{ route('data.export') }}">
                        <x-icon name="arrow-down-tray" class="w-5 h-5" />
                        Exportar para CSV
                    </x-button>
                </div>

                {{-- Importação de Dados --}}
                <div>
                    <h4 class="text-base font-medium mb-2">
                        Importar dados de um arquivo
                    </h4>
                    <p class="text-sm text-neutral-600 mb-4">
                        Restaure seus dados a partir de um arquivo de backup.
                        Selecione o arquivo .zip gerado pela função de exportação.
                    </p>

                    {{-- Mensagens de Sucesso/Erro --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex gap-3">
                                <x-icon name="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0" />
                                <div class="text-sm text-green-800">
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                    @elseif (session('warning'))
                        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex gap-3">
                                <x-icon name="exclamation-triangle" class="w-5 h-5 text-amber-600 flex-shrink-0" />
                                <div class="text-sm text-amber-800">
                                    <p class="font-medium">{{ session('warning') }}</p>
                                    @if (session('import_errors'))
                                        <details class="mt-3">
                                            <summary class="cursor-pointer hover:text-amber-900 select-none">
                                                <span class="underline">Ver {{ count(session('import_errors')) }}
                                                    {{ count(session('import_errors')) === 1 ? 'erro' : 'erros' }}</span>
                                            </summary>
                                            <div
                                                class="mt-2 p-3 bg-white rounded-lg border border-amber-200 max-h-60 overflow-y-auto">
                                                <ul class="space-y-1.5 text-xs">
                                                    @foreach (array_slice(session('import_errors'), 0, 50) as $error)
                                                        <li class="flex gap-2">
                                                            <span class="text-amber-600 flex-shrink-0">•</span>
                                                            <span>{{ $error }}</span>
                                                        </li>
                                                    @endforeach
                                                    @if (count(session('import_errors')) > 50)
                                                        <li class="pt-2 border-t border-amber-200 text-amber-700">
                                                            + {{ count(session('import_errors')) - 50 }}
                                                            {{ count(session('import_errors')) - 50 === 1 ? 'outro erro' : 'outros erros' }}
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif (session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex gap-3">
                                <x-icon name="x-circle" class="w-5 h-5 text-red-600 flex-shrink-0" />
                                <div class="text-sm text-red-800">
                                    <p class="font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('data.import') }}" enctype="multipart/form-data"
                        class="space-y-4" x-data="{
                            fileName: '',
                            loading: false,
                            fileSizeError: '',
                            maxFileSize: 10 * 1024 * 1024, // 10MB em bytes
                            validateFile(event) {
                                const file = event.target.files[0];
                                this.fileSizeError = '';
                        
                                if (file) {
                                    this.fileName = file.name;
                        
                                    if (file.size > this.maxFileSize) {
                                        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                                        this.fileSizeError = `O arquivo é muito grande (${sizeMB}MB). O tamanho máximo permitido é 10MB.`;
                                        event.target.value = '';
                                        this.fileName = '';
                                    }
                                }
                            }
                        }"
                        @submit="if (fileSizeError) { $event.preventDefault(); return false; } loading = true">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Arquivo de Backup (.zip) <span class="text-neutral-500 font-normal">(máx. 10MB)</span>
                            </label>
                            <div class="flex items-center gap-3">
                                {{-- Input real escondido --}}
                                <input type="file" name="backup_file" accept=".zip" required class="hidden"
                                    x-ref="fileInput" @change="validateFile($event)">

                                {{-- Área clicável (fake input) --}}
                                <div class="flex-1 relative cursor-pointer" @click="$refs.fileInput.click()">
                                    <div class="px-4 py-2 border-2 border-dashed border-neutral-300 rounded-lg hover:border-neutral-400 transition-colors"
                                        :class="{ 'border-red-300 bg-red-50': fileSizeError }">
                                        <div class="flex items-center gap-2 text-sm">
                                            <x-icon name="arrow-up-tray" class="w-5 h-5 text-neutral-500" />

                                            <span class="text-neutral-600" x-show="!fileName">Escolher arquivo</span>
                                            <span class="text-neutral-900 font-medium" x-show="fileName"
                                                x-text="fileName"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Erro de tamanho do arquivo --}}
                            <p x-show="fileSizeError" x-text="fileSizeError" class="mt-1 text-sm text-red-600"></p>

                            @error('backup_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-45">
                            <x-button type="submit" class="w-full">
                                <x-icon name="arrow-up-tray" class="w-5 h-5" />

                                Iniciar Importacao
                            </x-button>
                        </div>
                    </form>

                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex gap-2">
                            <x-icon name="information-circle" class="w-5 h-5 text-blue-600 flex-shrink-0" />
                            <div class="text-xs text-blue-800">
                                <p class="font-medium mb-1">Como funciona a importação:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    <li>Categorias duplicadas não serão importadas novamente</li>
                                    <li>Transações serão vinculadas às suas categorias correspondentes</li>
                                    <li>Transações sem categoria válida serão ignoradas</li>
                                    <li>Você receberá um relatório completo ao final</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                            <x-icon name="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0" />
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
                            <x-icon name="exclamation-triangle" class="w-5 h-5 text-amber-600 flex-shrink-0" />
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
