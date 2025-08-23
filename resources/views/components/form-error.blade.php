@props(['name'])

@error($name)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-x-2 text-sm text-red-500 mt-1.5 animate-shake']) }}>
        {{-- Ícone de Erro --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0">
            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75
         0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                clip-rule="evenodd" />
        </svg>

        {{-- Mensagem de Erro --}}
        <span class="font-semibold break-words">{{ $message }}</span>
    </div>
@enderror