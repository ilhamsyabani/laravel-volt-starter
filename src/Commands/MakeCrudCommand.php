<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{
    public $signature = 'volt-starter:crud
                        {model : Model name (e.g. Post, Product)}
                        {--fields= : Comma-separated fields (e.g. title:string,body:text,published:boolean)}
                        {--with-migration : Also generate a migration file}
                        {--force : Overwrite existing files}';

    public $description = 'Generate full CRUD pages (index, create, edit, show) for a model';

    protected string $model;
    protected string $modelPlural;
    protected string $modelLower;
    protected string $modelPluralLower;
    protected array  $fields = [];

    public function handle(): int
    {
        $this->model           = Str::studly($this->argument('model'));
        $this->modelPlural     = Str::pluralStudly($this->model);
        $this->modelLower      = Str::lower($this->model);
        $this->modelPluralLower = Str::lower($this->modelPlural);

        if ($this->option('fields')) {
            foreach (explode(',', $this->option('fields')) as $field) {
                [$name, $type] = explode(':', $field) + [null, 'string'];
                $this->fields[] = compact('name', 'type');
            }
        }

        $this->info("🏗️  Generating CRUD for <comment>{$this->model}</comment>...");
        $this->newLine();

        $this->generateIndex();
        $this->generateCreate();
        $this->generateEdit();

        $this->newLine();
        $this->info("✅ CRUD pages generated!");
        $this->line("  📋 Index  → <comment>/{$this->modelPluralLower}</comment>");
        $this->line("  ➕ Create → <comment>/{$this->modelPluralLower}/create</comment>");
        $this->line("  ✏️  Edit   → <comment>/{$this->modelPluralLower}/{{$this->modelLower}}/edit</comment>");

        return self::SUCCESS;
    }

    protected function generateIndex(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}");
        $file = "{$dir}/index.blade.php";

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        file_put_contents($file, $this->indexStub());
        $this->line("  ✓ Index page created");
    }

    protected function generateCreate(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}");
        $file = "{$dir}/create.blade.php";

        file_put_contents($file, $this->createStub());
        $this->line("  ✓ Create page created");
    }

    protected function generateEdit(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}/[{$this->modelLower}]");
        $file = "{$dir}/edit.blade.php";

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        file_put_contents($file, $this->editStub());
        $this->line("  ✓ Edit page created");
    }

    protected function indexStub(): string
    {
        $title = Str::headline($this->modelPluralLower);
        $model = $this->model;
        $mpl   = $this->modelPluralLower;
        $ml    = $this->modelLower;

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{state, computed};

middleware(['auth', 'verified']);

state(['search' => '', 'confirmDelete' => null]);

\$items = computed(fn() =>
    {$model}::query()
        ->when(\$this->search, fn(\$q) => \$q->where('name', 'like', "%{\$this->search}%"))
        ->latest()
        ->paginate(10)
);

\$delete = function (int \$id) {
    {$model}::findOrFail(\$id)->delete();
    \$this->dispatch('notify', type: 'success', message: '{$model} deleted.');
};

?>

<x-layouts.app>
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">{$title}</h1>
            <flux:button variant="primary" icon="plus" href="/{$mpl}/create" wire:navigate>
                Add {$model}
            </flux:button>
        </div>

        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" />

        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (\$this->items as \$item)
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3">{{ \$item->name }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <flux:button size="sm" href="/{$mpl}/{{ \$item->id }}/edit" wire:navigate>Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ \$item->id }})" wire:confirm="Delete this {$ml}?">Delete</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-8 text-center text-zinc-400">No {$mpl} found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ \$this->items->links() }}</div>

    </div>
</x-layouts.app>
BLADE;
    }

    protected function createStub(): string
    {
        $model = $this->model;
        $mpl   = $this->modelPluralLower;

        $stateFields = empty($this->fields)
            ? "['name' => '']"
            : '[' . implode(', ', array_map(fn($f) => "'{$f['name']}' => ''", $this->fields)) . ']';

        $formFields = empty($this->fields)
            ? "<flux:input wire:model=\"name\" label=\"Name\" />"
            : implode("\n        ", array_map(
                fn($f) => $f['type'] === 'text'
                    ? "<flux:textarea wire:model=\"{$f['name']}\" label=\"" . Str::headline($f['name']) . "\" />"
                    : "<flux:input wire:model=\"{$f['name']}\" label=\"" . Str::headline($f['name']) . "\" />",
                $this->fields
            ));

        $saveFields = empty($this->fields)
            ? "['name' => \$this->name]"
            : '[' . implode(', ', array_map(fn($f) => "'{$f['name']}' => \$this->{$f['name']}", $this->fields)) . ']';

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{state};

middleware(['auth', 'verified']);

state({$stateFields});

\$save = function () {
    \$this->validate(['name' => 'required|string|max:255']);

    {$model}::create({$saveFields});

    \$this->dispatch('notify', type: 'success', message: '{$model} created.');
    \$this->redirectRoute('{$mpl}.index', navigate: true);
};

?>

<x-layouts.app>
    <div class="max-w-xl space-y-6">
        <h1 class="text-2xl font-bold">Add {$model}</h1>

        <form wire:submit="save" class="space-y-4">
            {$formFields}

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">Save</flux:button>
                <flux:button href="/{$mpl}" wire:navigate>Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
BLADE;
    }

    protected function editStub(): string
    {
        $model = $this->model;
        $ml    = $this->modelLower;
        $mpl   = $this->modelPluralLower;

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{state, mount};

middleware(['auth', 'verified']);

state(['name' => '']);

mount(function ({$model} \${$ml}) {
    \$this->name = \${$ml}->name;
});

\$save = function ({$model} \${$ml}) {
    \$this->validate(['name' => 'required|string|max:255']);

    \${$ml}->update(['name' => \$this->name]);

    \$this->dispatch('notify', type: 'success', message: '{$model} updated.');
};

?>

<x-layouts.app>
    <div class="max-w-xl space-y-6">
        <h1 class="text-2xl font-bold">Edit {$model}</h1>

        <form wire:submit="save" class="space-y-4">
            <flux:input wire:model="name" label="Name" />

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">Update</flux:button>
                <flux:button href="/{$mpl}" wire:navigate>Cancel</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
BLADE;
    }
}
