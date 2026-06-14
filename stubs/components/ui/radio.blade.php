@props([
    'label'       => null,
    'options'     => [],   // [['value'=>'', 'label'=>'', 'description'=>'']]
    'name'        => null,
    'selected'    => null,
    'error'       => null,
    'orientation' => 'vertical', // vertical | horizontal
    'id'          => null,
])

@php
$groupId  = $id ?? 'radio-group-' . uniqid();
$hasError = $error || ($name && $errors->has($name));
$errorMsg = $error ?? ($name ? $errors->first($name) : '');
$wrapClass = $orientation === 'horizontal' ? 'flex flex-wrap gap-4' : 'space-y-2';
@endphp

<fieldset {{ $attributes->only('class') }}>
    @if ($label)
        <legend class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
            {{ $label }}
        </legend>
    @endif

    <div class="{{ $wrapClass }}" role="radiogroup">
        @foreach ($options as $option)
            @php
                $optId  = $groupId . '-' . $loop->index;
                $value  = is_array($option) ? $option['value'] : $option;
                $optLabel = is_array($option) ? ($option['label'] ?? $option['value']) : $option;
                $desc   = is_array($option) ? ($option['description'] ?? null) : null;
                $isChecked = $selected == $value || old($name) == $value;
            @endphp
            <div class="flex items-start gap-3">
                <div class="flex h-5 items-center">
                    <input
                        id="{{ $optId }}"
                        type="radio"
                        name="{{ $name }}"
                        value="{{ $value }}"
                        @checked($isChecked)
                        class="h-4 w-4 border-zinc-300 dark:border-zinc-600 text-[rgb(var(--vs-primary-600))] bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-[rgb(var(--vs-primary-500)/0.3)] focus:ring-offset-0 cursor-pointer transition {{ $hasError ? 'border-red-400' : '' }}"
                        @if($hasError) aria-invalid="true" @endif
                    />
                </div>
                <div>
                    <label for="{{ $optId }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 cursor-pointer">
                        {{ $optLabel }}
                    </label>
                    @if ($desc)
                        <p class="text-xs text-zinc-400">{{ $desc }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($hasError)
        <p class="mt-1 text-xs text-red-500">{{ $errorMsg }}</p>
    @endif
</fieldset>
