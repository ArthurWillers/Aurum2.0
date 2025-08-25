<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased bg-neutral-50 dark:bg-neutral-900 text-neutral-800 dark:text-white">

    <div x-data="{ open: false }">
        <aside
            class="fixed top-0 left-0 h-screen w-64 border-e bg-neutral-100 dark:bg-neutral-950 border-neutral-300 dark:border-neutral-800 p-4 flex flex-col gap-4 z-40
                   transition-transform duration-300 ease-in-out
                   lg:translate-x-0"
            :class="{ '-translate-x-full': !open }">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <x-app-logo />
                </a>

                <button @click="open = !open"
                    class="ms-auto lg:hidden cursor-pointer p-1 rounded-md hover:bg-neutral-200 dark:hover:bg-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d=" M6 18 18 6M6 6l12 12" />
                    </svg>

                </button>
            </div>

            <nav class="flex flex-col overflow-visible min-h-auto space-y-[2px]">
                <x-nav-link :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>

                    Dashboard
                </x-nav-link>

                <x-nav-link>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>

                    Receitas
                </x-nav-link>

                <x-nav-link>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                    </svg>

                    Despesas
                </x-nav-link>

                <x-nav-link>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>

                    Categorias
                </x-nav-link>

            </nav>

            <x-dropdown position="top" class="mt-auto hidden lg:block" accent contentClass="w-full">
                <x-slot name="trigger">
                    <button
                        class="w-full flex items-center rounded-lg p-1 hover:bg-neutral-800/5 dark:hover:bg-white/10 group cursor-pointer">
                        <div
                            class="shrink-0 border rounded-md p-1 font-medium bg-neutral-200 border-neutral-300 dark:bg-neutral-600 dark:border-neutral-500">
                            {{ auth()->user()->initials() }}
                        </div>
                        <span
                            class="mx-2 text-sm font-medium truncate dark:text-white/80 dark:group-hover:text-white text-neutral-800/80 group-hover:text-neutral-800">{{ auth()->user()->name }}</span>
                        <div
                            class="ms-auto dark:text-white/80 dark:group-hover:text-white text-neutral-800/80 group-hover:text-neutral-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d=" M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </div>
                </x-slot>

                <x-slot name="content">
                    <div class="flex items-center gap-2 p-2">
                        <div
                            class="shrink-0 border rounded-md p-1 font-medium bg-neutral-200 border-neutral-300 dark:bg-neutral-600 dark:border-neutral-500">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="truncate">
                            <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 truncate">
                                {{ auth()->user()->name }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                {{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    <hr class="my-1 border-neutral-300 dark:border-neutral-700">

                    <a href="#" @click="open = !open"
                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        Configurações
                    </a>
                    <form method="POST" id="logout" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" @click="open = !open"
                            class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 dark:hover:bg-neutral-800 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="size-5">
                                <path fill-rule="evenodd"
                                    d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                    clip-rule="evenodd" />
                                <path fill-rule="evenodd"
                                    d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z"
                                    clip-rule="evenodd" />
                            </svg>
                            Sair
                        </button>
                    </form>

                </x-slot>
            </x-dropdown>

        </aside>

        <div x-show="open" @click="open = false" class="fixed inset-0 bg-black/10 z-30 lg:hidden" x-cloak></div>

        <header class="flex items-center px-6 w-full min-h-14 lg:hidden">
            <button @click="open = !open"
                class="cursor-pointer rounded-lg p-1 hover:bg-neutral-200 dark:hover:bg-neutral-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <x-dropdown position="bottom-end" class="ms-auto" accent contentClass="w-60">
                <x-slot name="trigger">
                    <button
                        class="w-full flex items-center rounded-lg p-1 hover:bg-neutral-800/5 dark:hover:bg-white/10 group cursor-pointer gap-2">
                        <div
                            class="shrink-0 border rounded-md p-1 font-medium bg-neutral-200 border-neutral-300 dark:bg-neutral-600 dark:border-neutral-500">
                            {{ auth()->user()->initials() }}
                        </div>

                        <div
                            class="ms-auto dark:text-white/80 dark:group-hover:text-white text-neutral-800/80 group-hover:text-neutral-800">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="flex items-center gap-2 p-2">
                        <div
                            class="shrink-0 border rounded-md p-1 font-medium bg-neutral-200 border-neutral-300 dark:bg-neutral-600 dark:border-neutral-500">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="truncate">
                            <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 truncate">
                                {{ auth()->user()->name }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                {{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    <hr class="my-1 border-neutral-300 dark:border-neutral-700">

                    <a href="#" @click="open = !open"
                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-neutral-700 hover:bg-neutral-200 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        Configurações
                    </a>

                    <button type="submit" form="logout" @click="open = !open"
                        class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm text-red-500 hover:bg-neutral-200 dark:hover:bg-neutral-800 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-5">
                            <path fill-rule="evenodd"
                                d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z"
                                clip-rule="evenodd" />
                        </svg>
                        Sair
                    </button>

                </x-slot>
            </x-dropdown>
        </header>
    </div>
    <main class="lg:ml-64 p-6">
        {{ $slot }}
    </main>

</body>

</html>
