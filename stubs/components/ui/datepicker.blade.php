{{--
    Date Picker - Simple date selection

    Usage:
    <x-ui.datepicker wire:model="date" label="Select Date" />
    <x-ui.datepicker wire:model="date" :min="now()->toDateString()" />
--}}
@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'min' => null,
    'max' => null,
    'placeholder' => 'Select date',
])

@php
$inputId = $name ?? 'date-' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.25 2a.75.75 0 01.75-.75h8.5a.75.75 0 010 1.5h-.75V4a.75.75 0 01-.22.53l-2.25 2.25a.75.75 0 01-1.06 0L8.75 5.78V4h.75a.75.75 0 010 1.5h-4.5a.75.75 0 01-.75-.75V2zM4.25 8a.75.75 0 01.75-.75h8.5a.75.75 0 010 1.5h-.75v.97a.75.75 0 01-.22.53l-2.25 2.25a.75.75 0 01-1.06 0l-2.25-2.25a.75.75 0 01-.22-.53V9.75h-.75a.75.75 0 01-.75-.75V8z" clip-rule="evenodd"/>
            </svg>
        </div>

        <input
            type="date"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ $value }}"
            min="{{ $min }}"
            max="{{ $max }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->except(['class', 'id', 'name', 'value', 'min', 'max', 'placeholder'])->class([
                'block w-full rounded-[var(--vs-radius)] border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 pl-10 pr-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[rgb(var(--vs-primary-500))] focus:outline-none focus:ring-2 focus:ring-[rgb(var(--vs-primary-500)/0.2)]'
            ])->merge(['class' => 'block w-full rounded-[var(--vs-radius)] border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 pl-10 pr-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-[rgb(var(--vs-primary-500))] focus:outline-none focus:ring-2 focus:ring-[rgb(var(--vs-primary-500)/0.2)]']) }}
        />
    </div>
</div>