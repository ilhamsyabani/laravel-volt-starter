@props([
    'label'       => null,
    'hint'        => null,
    'error'       => null,       // string pesan error
    'icon'        => null,       // heroicon leading
    'iconTrailing'=> null,       // heroicon trailing
    'type'        => 'text',
    'id'          => null,
    'required'    => false,
])

@php
$inputId   = $id ?? 'input-' . uniqid();
$hasError  = $error || ($errors->has($attributes->get('name', '')) && !$error);
$errorMsg  = $error ?? $errors->first($attributes->get('name', ''));

$base = 'block w-full rounded-[var(--vs-radius)] border bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 text-sm transition focus:outline-none focus:ring-2 focus:ring-offset-0';

$normal = 'border-zinc-300 dark:border-zinc-600 focus:border-[rgb(var(--vs-primary-500))] focus:ring-[rgb(var(--vs-primary-500)/0.2)]';
$errored= 'border-red-400 dark:border-red-500 focus:border-red-400 focus:ring-red-400/20 pr-10';

$padding = match(true) {
    (bool)$icon && (bool)$iconTrailing => 'pl-10 pr-10 py-2',
    (bool)$icon     => 'pl-10 pr-3 py-2',
    (bool)$iconTrailing => 'pl-3 pr-10 py-2',
    default => 'px-3 py-2',
};

$inputClass = $base . ' ' . ($hasError ? $errored : $normal) . ' ' . $padding;
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>

    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">

        @if ($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                @svg($icon, 'w-4 h-4 text-zinc-400')
            </div>
        @endif

        <input
            id="{{ $inputId }}"
            type="{{ $type }}"
            {{ $attributes->except(['class', 'id'])->merge([
                'class' => $inputClass
            ]) }}
            @if($hasError) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            @if($hint && !$hasError) aria-describedby="{{ $inputId }}-hint" @endif
        />

        @if ($iconTrailing && !$hasError)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                @svg($iconTrailing, 'w-4 h-4 text-zinc-400')
            </div>
        @endif

        @if ($hasError)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-4 h-4 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
            </div>
        @endif

    </div>

    @if ($hasError)
        <p id="{{ $inputId }}-error" class="text-xs text-red-500 flex items-center gap-1">
            {{ $errorMsg }}
        </p>
    @elseif ($hint)
        <p id="{{ $inputId }}-hint" class="text-xs text-zinc-400">{{ $hint }}</p>
    @endif

</div>
