<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakePageCommand extends Command
{
    public $signature = 'volt-starter:page
                        {name : The name of the page (e.g. users/index)}
                        {--auth : Add auth middleware}
                        {--admin : Add admin role middleware}
                        {--superadmin : Add superadmin role middleware}
                        {--bare : No boilerplate, just a blank Volt component}';

    public $description = 'Generate a new Livewire Volt + Folio page';

    public function handle(): int
    {
        $name    = $this->argument('name');
        $parts   = explode('/', $name);
        $pageName = array_pop($parts);
        $subDir  = implode('/', $parts);

        $targetDir = resource_path('views/pages' . ($subDir ? "/{$subDir}" : ''));
        $targetFile = "{$targetDir}/{$pageName}.blade.php";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (file_exists($targetFile) && !$this->confirm("File {$targetFile} already exists. Overwrite?")) {
            $this->warn('Cancelled.');
            return self::FAILURE;
        }

        $stub = $this->option('bare') ? $this->getBareStub($pageName) : $this->getFullStub($pageName, $this->option('auth'));

        file_put_contents($targetFile, $stub);

        $this->info("✅ Page created: <comment>{$targetFile}</comment>");
        $this->line("   URL: <comment>/" . str_replace('.blade.php', '', $name) . "</comment>");

        return self::SUCCESS;
    }

    protected function getBareStub(string $name): string
    {
        $title = Str::headline($name);
        return <<<BLADE
<?php

use function Livewire\Volt\{state};

state([]);

?>

<x-layouts.app title="{$title}">
    <div>
        <h1>{$title}</h1>
    </div>
</x-layouts.app>
BLADE;
    }

    protected function getFullStub(string $name, bool $withAuth): string
    {
        $title    = Str::headline($name);
        $varName  = Str::camel($name);
        $authLine = $withAuth ? "\nmiddleware(['auth', 'verified']);\n" : '';

        return <<<BLADE
<?php

use function Livewire\Volt\{state, computed, mount};
{$authLine}
state([
    'search' => '',
]);

mount(function () {
    // Initialization logic here
});

\$items = computed(function () {
    // Return your data here
    return collect([]);
});

?>

<x-layouts.app title="{$title}">
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{$title}</h1>
                <p class="mt-1 text-sm text-zinc-500">Manage your {$title} here.</p>
            </div>
            <x-ui.button icon="plus">
                Add New
            </x-ui.button>
        </div>

        {{-- Search --}}
        <x-ui.input
            wire:model.live.debounce.300ms="search"
            placeholder="Search..."
            icon="magnifying-glass"
        />

        {{-- Content --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse (\$this->items as \$item)
                    <div class="p-4">
                        {{ \$item }}
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400">
                        No items found.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-layouts.app>
BLADE;
    }
}
