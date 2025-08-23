@props([
    'variant' => 'underline', // 'underline', 'ghost'
    'external' => false,
])

@php
    // Classes de cor e base, agora fixas no estilo zinc
    $baseClasses = 'font-semibold transition-colors duration-300 text-zinc-700 dark:text-zinc-100 decoration-zinc-700/30 dark:decoration-zinc-100/30 hover:decoration-zinc-700 dark:hover:decoration-zinc-100';

    // Classes de variante (com ou sem sublinhado inicial)
    $variantClasses = match ($variant) {
        'ghost' => 'hover:underline underline-offset-4',
        default => 'underline underline-offset-4',
    };

    $finalClasses = implode(' ', [$baseClasses, $variantClasses]);
@endphp

<a {{ $attributes->merge(['class' => $finalClasses])}}>
    {{ $slot }}
</a>