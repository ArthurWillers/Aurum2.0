@props([
    'position' => 'top', // Valor padrão: abre para cima
    'contentClass' => '',
    'accent' => false,
])

<div x-data="{ open: false }" @click.outside="open = false" {{ $attributes->merge(['class' => 'relative']) }}>
    
    {{-- Slot para o gatilho (Botão) --}}
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{-- Slot para o conteudo --}}
    <div 
        x-show="open" 
        x-transition
        x-cloak
        @class([
            'absolute z-50 rounded-lg border bg-white p-1 shadow-lg dark:bg-neutral-900',
            'bottom-full mb-2' => $position === 'top', // Abre para cima
            'top-full mt-2' => $position === 'bottom', // Abre para baixo
            'border-accent' => $accent,
            'border-neutral-300 dark:border-neutral-700' => !$accent,
            $contentClass,
        ])
    >
        {{ $content }}
    </div>

</div>