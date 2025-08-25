@props([
    'href' => '#',
    'current' => false,
])

<a href="{{ $href }}" {{ $attributes->class([
        // Classes base, sempre aplicadas
        'rounded-lg h-10 lg:h-8 gap-3 flex relative items-center w-full text-sm px-3 text-start',
        // Classes para o estado ATIVO
        'bg-neutral-50 dark:bg-white/10 border border-neutral-300 dark:border-none' => $current,
        // Classes para o estado INATIVO
        'text-neutral-500 hover:bg-black/7 dark:text-neutral-200 dark:hover:bg-white/10 hover:text-neutral-800 dark:hover:text-white' => !$current,
    ]) }}>
    {{ $slot }}
</a>