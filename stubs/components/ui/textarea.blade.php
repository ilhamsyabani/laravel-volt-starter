@props([
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'rows'     => 4,
    'maxlength'=> null,
    'id'       => null,
    'required' => false,
    'resize'   => true,  // false = no-resize
])

@php
$textareaId = $id ?? 'textarea-' . uniqid();
$hasError   = $error || $errors->has($attributes->get('name', ''));
$errorMsg   = $error ?? $errors->first($attributes->get('name', ''));

$base = 'block w-full rounded-[var(--vs-radius)] border bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 text-sm px-3 py-2 transition focus:outline-none focus:ring-2 focus:ring-offset-0';
$normal  = 'border-zinc-300 dark:border-zinc-600 focus:border-[rgb(var(--vs-primary-500))] focus:ring-[rgb(var(--vs-primary-500)/0.2)]';
$errored = 'border-red-400 dark:border-red-500 focus:border-red-400 focus:ring-red-400/20';
$resizeClass = $resize ? 'resize-y' : 'resize-none';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>

    @if ($label)
        <div class="flex items-center justify-between">
            <label for="{{ $textareaId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                {{ $label }}
                @if ($required)<span class="text-red-500 ml-0.5" aria-hidden="true">*</span>@endif
            </label>
            @if ($maxlength)
                <span
                    class="text-xs text-zinc-400"
                    x-data="{ count: $el.closest('.space-y-1').querySelector('textarea').value.length }"
                    x-text="count + '/{{ $maxlength }}'"
                    @input.window="count = $el.closest('.space-y-1').querySelector('textarea').value.length"
                ></span>
            @endif
        </div>
    @endif

    <textarea
        id="{{ $textareaId }}"
        rows="{{ $rows }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->except(['class', 'id', 'rows'])->merge([
            'class' => $base . ' ' . ($hasError ? $errored : $normal) . ' ' . $resizeClass
        ]) }}
        @if($hasError) aria-invalid="true" aria-describedby="{{ $textareaId }}-error" @endif
    >{{ $slot }}</textarea>

    @if ($hasError)
        <p id="{{ $textareaId }}-error" class="text-xs text-red-500">{{ $errorMsg }}</p>
    @elseif ($hint)
        <p class="text-xs text-zinc-400">{{ $hint }}</p>
    @endif

</div>
