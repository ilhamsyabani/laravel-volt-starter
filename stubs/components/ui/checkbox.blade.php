@props([
    'label'         => null,
    'description'   => null,
    'error'         => null,
    'id'            => null,
    'indeterminate' => false,
])

@php
$checkId  = $id ?? 'checkbox-' . uniqid();
$hasError = $error || $errors->has($attributes->get('name', ''));
$errorMsg = $error ?? $errors->first($attributes->get('name', ''));
@endphp

<div class="flex gap-3">
    <div class="flex h-5 items-center">
        <input
            id="{{ $checkId }}"
            type="checkbox"
            {{ $attributes->merge([
                'class' => 'h-4 w-4 rounded border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-[rgb(var(--vs-primary-600))] transition focus:ring-2 focus:ring-[rgb(var(--vs-primary-500)/0.3)] focus:ring-offset-0 cursor-pointer ' . ($hasError ? 'border-red-400' : '')
            ]) }}
            @if($indeterminate) x-init="$el.indeterminate = true" @endif
            @if($hasError) aria-invalid="true" aria-describedby="{{ $checkId }}-error" @endif
        />
    </div>
    @if ($label || $description || $slot->isNotEmpty())
        <div class="space-y-0.5">
            @if ($label)
                <label for="{{ $checkId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 cursor-pointer">
                    {{ $label }}
                </label>
            @endif
            @if ($description)
                <p class="text-xs text-zinc-400">{{ $description }}</p>
            @endif
            {{ $slot }}
            @if ($hasError)
                <p id="{{ $checkId }}-error" class="text-xs text-red-500">{{ $errorMsg }}</p>
            @endif
        </div>
    @endif
</div>
