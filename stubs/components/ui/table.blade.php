{{--
    Table wrapper — gunakan dengan <table> standar di dalamnya.

    Contoh:
    <x-ui.table>
        <x-slot:head>
            <x-ui.table.th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">Name</x-ui.table.th>
            <x-ui.table.th>Email</x-ui.table.th>
            <x-ui.table.th align="right">Actions</x-ui.table.th>
        </x-slot:head>

        @forelse ($users as $user)
            <x-ui.table.row>
                <x-ui.table.td>{{ $user->name }}</x-ui.table.td>
                <x-ui.table.td>{{ $user->email }}</x-ui.table.td>
                <x-ui.table.td align="right">...</x-ui.table.td>
            </x-ui.table.row>
        @empty
            <x-ui.table.empty colspan="3" message="No users found." />
        @endforelse
    </x-ui.table>
--}}

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-[var(--vs-radius-lg)] border border-zinc-200 dark:border-zinc-700']) }}>
    <table class="w-full text-sm">
        <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
            <tr>{{ $head }}</tr>
        </thead>
        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
            {{ $slot }}
        </tbody>
    </table>
</div>
