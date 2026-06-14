@props([
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'options'  => [],    // ['value' => 'label'] atau [['value'=>'','label'=>'']]
    'placeholder' => 'Select an option...',
    'id'       => null,
    'required' => false,
])

@php
$selectId = $id ?? 'select-' . uniqid();
$hasError = $error || $errors->has($attributes->get('name', ''));
$errorMsg = $error ?? $errors->first($attributes->get('name', ''));

$base = 'block w-full rounded-[var(--vs-radius)] border bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2 pr-10 transition focus:outline-none focus:ring-2 focus:ring-offset-0 appearance-none cursor-pointer';
$normal  = 'border-zinc-300 dark:border-zinc-600 focus:border-[rgb(var(--vs-primary-500))] focus:ring-[rgb(var(--vs-primary-500)/0.2)]';
$errored = 'border-red-400 dark:border-red-500 focus:border-red-400 focus:ring-red-400/20';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>

    @if ($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            {{ $label }}
            @if ($required)<span class="text-red-500 ml-0.5" aria-hidden="true">*</span>@endif
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $selectId }}"
            {{ $attributes->except(['class', 'id'])->merge([
                'class' => $base . ' ' . ($hasError ? $errored : $normal)
            ]) }}
            @if($hasError) aria-invalid="true" aria-describedby="{{ $selectId }}-error" @endif
        >
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach ($options as $value => $label)
                @if (is_array($label))
                    <option value="{{ $label['value'] }}" @selected(old($attributes->get('name'), $attributes->get('value')) == $label['value'])>
                        {{ $label['label'] }}
                    </option>
                @else
                    <option value="{{ $value }}" @selected(old($attributes->get('name'), $attributes->get('value')) == $value)>
                        {{ $label }}
                    </option>
                @endif
            @endforeach

            {{ $slot }}
        </select>

        {{-- Custom chevron --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>

    @if ($hasError)
        <p id="{{ $selectId }}-error" class="text-xs text-red-500">{{ $errorMsg }}</p>
    @elseif ($hint)
        <p class="text-xs text-zinc-400">{{ $hint }}</p>
    @endif

</div>
