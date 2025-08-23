@props([
    'color' => 'default',
    'href' => null,
    'type' => 'button',
    'icon' => '',
])

@php
    $baseClasses =
        'cursor-pointer inline-flex items-center justify-center font-semibold px-3 py-2 text-sm rounded-lg disabled:opacity-75 disabled:cursor-default gap-1';

    $colorClasses = match ($color) {
        'red' => 'bg-red-500 hover:bg-red-700 text-white',
        'accent' => 'bg-accent hover:bg-[color-mix(in_srgb,var(--color-accent),#000_10%)] text-white',
        'none' => '',
        default => 'bg-zinc-800 dark:bg-white hover:bg-zinc-700 dark:hover:bg-zinc-300 text-white dark:text-zinc-800 border border-black/10 dark:border-0',
    };

    $shadowClasses = match ($color) {
        'none' => '',
        default => 'shadow-[inset_0px_1px_--theme(--color-white/.5)]',
    };

    $finalClasses = implode(' ', [$baseClasses, $colorClasses, $shadowClasses]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $finalClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $finalClasses]) }}>
        {{ $slot }}
    </button>
@endif
