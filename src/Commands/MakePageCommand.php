<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakePageCommand extends Command
{
    public $signature = 'volt-starter:page
                        {name : The name of the page (e.g. users/index)}
                        {--auth : Add auth middleware}
                        {--admin : Add admin-only middleware}
                        {--superadmin : Add superadmin-only middleware}
                        {--bare : No boilerplate, just a blank Volt component}';

    public $description = 'Generate a new Livewire Volt + Folio page';

    public function handle(): int
    {
        $name     = $this->argument('name');
        $parts    = explode('/', $name);
        $pageName = array_pop($parts);
        $subDir   = implode('/', $parts);

        $targetDir  = resource_path('views/pages' . ($subDir ? "/{$subDir}" : ''));
        $targetFile = "{$targetDir}/{$pageName}.blade.php";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (file_exists($targetFile) && !$this->confirm("File {$targetFile} already exists. Overwrite?")) {
            $this->warn('Cancelled.');
            return self::FAILURE;
        }

        $stub = $this->option('bare')
            ? $this->getBareStub($pageName)
            : $this->getFullStub($pageName);

        file_put_contents($targetFile, $stub);

        $this->info("✅ Page created: <comment>{$targetFile}</comment>");
        $this->line("   URL: <comment>/" . str_replace('.blade.php', '', $name) . "</comment>");

        if ($this->option('auth')) {
            $this->line("   Middleware: <comment>auth, verified</comment>");
        } elseif ($this->option('admin')) {
            $this->line("   Middleware: <comment>auth, verified, role:admin</comment>");
        } elseif ($this->option('superadmin')) {
            $this->line("   Middleware: <comment>auth, verified, role:superadmin</comment>");
        }

        return self::SUCCESS;
    }

    protected function getMiddlewareLine(): string
    {
        if ($this->option('superadmin')) {
            return "\nmiddleware(['auth', 'verified', 'role:superadmin']);\n";
        }

        if ($this->option('admin')) {
            return "\nmiddleware(['auth', 'verified', 'role:admin']);\n";
        }

        if ($this->option('auth')) {
            return "\nmiddleware(['auth', 'verified']);\n";
        }

        return '';
    }

    protected function getBareStub(string $name): string
    {
        $title = Str::headline($name);
        $middleware = $this->getMiddlewareLine();

        return <<<BLADE
<?php

use function Livewire\Volt\{state};
{$middleware}
state([]);

?>

<x-layouts.app>
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{$title}</h1>
    </div>
</x-layouts.app>
BLADE;
    }

    protected function getFullStub(string $name): string
    {
        $title       = Str::headline($name);
        $middleware  = $this->getMiddlewareLine();

        return <<<BLADE
<?php

use function Livewire\Volt\{state, computed};
use App\Models\User;
{$middleware}

state([
    'search' => '',
]);

\$users = computed(function () {
    return User::query()
        ->when(\$this->search, fn (\$q) => \$q->where('name', 'like', "%{\$this->search}%"))
        ->latest()
        ->paginate(10);
});

?>

<x-layouts.app>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{$title}</h1>
                <p class="mt-1 text-sm text-zinc-500">Manage your {$title} here.</p>
            </div>
            <x-ui.button icon="plus" href="/create">
                Add New
            </x-ui.button>
        </div>

        {{-- Search --}}
        <x-ui.input
            wire:model.live.debounce.300ms="search"
            placeholder="Search..."
            icon="magnifying-glass"
        />

        {{-- Table --}}
        <x-ui.table :data="\$this->users" :columns="['name', 'email']" />

        {{-- Pagination --}}
        <div>
            {{ \$this->users->links() }}
        </div>

    </div>
</x-layouts.app>
BLADE;
    }
}