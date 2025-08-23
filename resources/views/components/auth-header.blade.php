@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    {{-- Título --}}
    <div class="text-2xl font-medium text-zinc-800 dark:text-white mb-2">
        {{ $title }}
    </div>

    {{-- Subtítulo --}}
    <div class="text-sm text-zinc-500 dark:text-white/70">
        {{ $description }}
    </div>
</div>