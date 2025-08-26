<x-layouts.app>
    {{-- Cabeçalho --}}
    <div class="mb-6">
        <h2 class="text-2xl font-medium mb-2">
            Configurações
        </h2>
        <p class="mt-1 text-base text-neutral-600 dark:text-gray-400">
            Gerencie seu perfil e configurações da conta.
        </p>
    </div>

    {{-- Divisor --}}
    <hr class="border-neutral-200 dark:border-neutral-700 mb-6" />

    <div class="max-w-2xl mx-auto space-y-12">

        {{-- Seletor de Aparência (claro/escuro/sistema) --}}
        <section>
            <header>
                <h3 class="text-lg font-medium">
                    Aparência
                </h3>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Atualize as configurações de aparência da sua conta.
                </p>
            </header>

            {{-- Seletor de Tema Triplo --}}
            <div class="mt-6">
                <fieldset class="flex gap-2 items-center bg-neutral-200 dark:bg-neutral-700 rounded-lg p-1">
                    <legend class="sr-only">Tema</legend>
                    <label class="flex-1">
                        <input type="radio" name="theme" value="light" id="theme-light" class="peer sr-only" />
                        <div
                            class="flex flex-col items-center justify-center py-2 rounded-md transition-colors cursor-pointer
                        peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                        peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                        text-neutral-700 dark:text-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <circle cx="12" cy="12" r="4" />
                                <path d="M12 2v2" />
                                <path d="M12 20v2" />
                                <path d="m4.93 4.93 1.41 1.41" />
                                <path d="m17.66 17.66 1.41 1.41" />
                                <path d="M2 12h2" />
                                <path d="M20 12h2" />
                                <path d="m6.34 17.66-1.41 1.41" />
                                <path d="m19.07 4.93-1.41 1.41" />
                            </svg>
                            <span class="text-xs font-medium">Claro</span>
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="theme" value="dark" id="theme-dark" class="peer sr-only" />
                        <div
                            class="flex flex-col items-center justify-center py-2 rounded-md transition-colors cursor-pointer
                        peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                        peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                        text-neutral-700 dark:text-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                            </svg>
                            <span class="text-xs font-medium">Escuro</span>
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="theme" value="system" id="theme-system" class="peer sr-only" />
                        <div
                            class="flex flex-col items-center justify-center py-2 rounded-md transition-colors cursor-pointer
                        peer-checked:bg-white peer-checked:dark:bg-neutral-900 peer-checked:shadow-sm
                        peer-checked:text-neutral-900 peer-checked:dark:text-neutral-100
                        text-neutral-700 dark:text-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <rect width="20" height="14" x="2" y="3" rx="2" />
                                <line x1="8" x2="16" y1="21" y2="21" />
                                <line x1="12" x2="12" y1="17" y2="21" />
                            </svg>
                            <span class="text-xs font-medium">Sistema</span>
                        </div>
                    </label>
                </fieldset>
            </div>
        </section>

        <hr class="border-neutral-200 dark:border-neutral-700" />

        
    </div>

    {{-- Lógica JavaScript para o seletor de tema --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="theme"]');

            function applyTheme(theme) {
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)')
                        .matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const newTheme = radio.value;
                    if (newTheme === 'system') {
                        localStorage.removeItem('theme');
                    } else {
                        localStorage.setItem('theme', newTheme);
                    }
                    const currentTheme = localStorage.getItem('theme') || 'system';
                    applyTheme(currentTheme);
                });
            });

            // Inicializa o radio selecionado e aplica o tema
            const initialTheme = localStorage.getItem('theme') || 'system';
            const initialRadio = document.getElementById(`theme-${initialTheme}`);
            if (initialRadio) initialRadio.checked = true;
            applyTheme(initialTheme);

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!('theme' in localStorage)) {
                    applyTheme('system');
                }
            });
        });
    </script>

</x-layouts.app>
