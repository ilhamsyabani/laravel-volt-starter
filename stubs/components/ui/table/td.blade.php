@props([
    'align' => 'left', // left | center | right
])

@php
$alignClass = ['left' => 'text-left', 'center' => 'text-center', 'right' => 'text-right'][$align] ?? 'text-left';
@endphp

<td {{ $attributes->merge(['class' => "px-4 py-3 text-zinc-700 dark:text-zinc-200 {$alignClass}"]) }}>
    {{ $slot }}
</td>
