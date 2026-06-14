<?php

namespace Ilhamsyabani\VoltStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{
    public $signature = 'volt-starter:crud
                        {model : Model name (e.g. Post, Product)}
                        {--fields= : Comma-separated fields (e.g. title:string,body:text,status:select:pending,active)}
                        {--with-migration : Generate migration file}
                        {--force : Overwrite existing files}';

    public $description = 'Generate full CRUD pages (index, create, edit, show) for a model using Volt + Folio';

    protected string $model;
    protected string $modelPlural;
    protected string $modelLower;
    protected string $modelPluralLower;
    protected array  $fields = [];

    public function handle(): int
    {
        $this->model            = Str::studly($this->argument('model'));
        $this->modelPlural      = Str::pluralStudly($this->model);
        $this->modelLower       = Str::lower($this->model);
        $this->modelPluralLower = Str::lower($this->modelPlural);

        // Parse fields: name:type OR name:type:options (for select)
        if ($this->option('fields')) {
            foreach (explode(',', $this->option('fields')) as $field) {
                $parts = explode(':', $field);
                $name = $parts[0];
                $type = $parts[1] ?? 'string';
                $options = isset($parts[2]) ? explode('|', $parts[2]) : null;
                $this->fields[] = compact('name', 'type', 'options');
            }
        }

        $this->info("🏗️  Generating CRUD for <comment>{$this->model}</comment>...");
        $this->newLine();

        $this->generateIndex();
        $this->generateCreate();
        $this->generateEdit();
        $this->generateShow();

        if ($this->option('with-migration')) {
            $this->generateMigration();
        }

        $this->newLine();
        $this->info("✅ CRUD pages generated!");
        $this->line("  📋 Index  → <comment>/{$this->modelPluralLower}</comment>");
        $this->line("  ➕ Create → <comment>/{$this->modelPluralLower}/create</comment>");
        $this->line("  📝 Edit   → <comment>/{$this->modelPluralLower}/{{$this->modelLower}}/edit</comment>");
        $this->line("  👁️  Show   → <comment>/{$this->modelPluralLower}/{{$this->modelLower}}</comment>");

        if ($this->option('with-migration')) {
            $this->line("  🗄️  Migration created");
        }

        return self::SUCCESS;
    }

    protected function generateIndex(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}");
        $file = "{$dir}/index.blade.php";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (file_exists($file) && !$this->option('force')) {
            $this->warn("  ⚠️  Index exists, skipping...");
            return;
        }

        file_put_contents($file, $this->indexStub());
        $this->line("  ✓ Index page created");
    }

    protected function generateCreate(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}");
        $file = "{$dir}/create.blade.php";

        if (file_exists($file) && !$this->option('force')) {
            $this->warn("  ⚠️  Create exists, skipping...");
            return;
        }

        file_put_contents($file, $this->createStub());
        $this->line("  ✓ Create page created");
    }

    protected function generateEdit(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}/[{$this->modelLower}]");
        $file = "{$dir}/edit.blade.php";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (file_exists($file) && !$this->option('force')) {
            $this->warn("  ⚠️  Edit exists, skipping...");
            return;
        }

        file_put_contents($file, $this->editStub());
        $this->line("  ✓ Edit page created");
    }

    protected function generateShow(): void
    {
        $dir  = resource_path("views/pages/{$this->modelPluralLower}/[{$this->modelLower}]");
        $file = "{$dir}/show.blade.php";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (file_exists($file) && !$this->option('force')) {
            $this->warn("  ⚠️  Show exists, skipping...");
            return;
        }

        file_put_contents($file, $this->showStub());
        $this->line("  ✓ Show page created");
    }

    protected function generateMigration(): void
    {
        $dir  = database_path('migrations');
        $file = "{$dir}/create_{$this->modelPluralLower}_table.php";

        if (file_exists($file) && !$this->option('force')) {
            $this->warn("  ⚠️  Migration exists, skipping...");
            return;
        }

        file_put_contents($file, $this->migrationStub());
        $this->line("  ✓ Migration created");
    }

    protected function buildStateFields(): string
    {
        if (empty($this->fields)) {
            return "['name' => '']";
        }

        $parts = [];
        foreach ($this->fields as $field) {
            $default = $field['type'] === 'boolean' ? 'false' : "''";
            $parts[] = "'{$field['name']}' => {$default}";
        }

        return '[' . implode(', ', $parts) . ']';
    }

    protected function buildValidationRules(): string
    {
        if (empty($this->fields)) {
            return "\n    \$this->validate(['name' => 'required|string|max:255']);";
        }

        $rules = ["'id' => 'sometimes|exists:{$this->modelPluralLower},id'"];
        foreach ($this->fields as $field) {
            $rule = "'{$field['name']}' => 'required|" . $this->getValidationType($field['type']) . "'";
            $rules[] = $rule;
        }

        return "\n    \$this->validate([" . implode(', ', $rules) . "]);";
    }

    protected function getValidationType(string $type): string
    {
        return match ($type) {
            'integer', 'bigint' => 'integer',
            'boolean' => 'boolean',
            'text' => 'string',
            'email' => 'email',
            'url' => 'url',
            'date' => 'date',
            'datetime' => 'date',
            'select' => 'string',
            default => 'string|max:255',
        };
    }

    protected function buildFormFields(): string
    {
        if (empty($this->fields)) {
            return '<x-ui.input wire:model="name" label="Name" required />';
        }

        $forms = [];
        foreach ($this->fields as $field) {
            $label = Str::headline($field['name']);
            $required = "required";

            $forms[] = match ($field['type']) {
                'text' => "<x-ui.textarea wire:model=\"{$field['name']}\" label=\"{$label}\" {$required} />",
                'boolean' => "<x-ui.toggle wire:model=\"{$field['name']}\" label=\"{$label}\" />",
                'select' => "<x-ui.select wire:model=\"{$field['name']}\" label=\"{$label}\" :options=\"['" . implode("', '", $field['options'] ?? []) . "']\" {$required} />",
                default => "<x-ui.input wire:model=\"{$field['name']}\" label=\"{$label}\" {$required} />",
            };
        }

        return implode("\n            ", $forms);
    }

    protected function buildCreateData(): string
    {
        if (empty($this->fields)) {
            return "['name' => \$this->name]";
        }

        $parts = [];
        foreach ($this->fields as $field) {
            if ($field['type'] === 'boolean') {
                $parts[] = "'{$field['name']}' => \$this->{$field['name']} ?? false";
            } else {
                $parts[] = "'{$field['name']}' => \$this->{$field['name']}";
            }
        }

        return '[' . implode(', ', $parts) . ']';
    }

    protected function buildUpdateData(): string
    {
        if (empty($this->fields)) {
            return "['name' => \$this->name]";
        }

        $parts = [];
        foreach ($this->fields as $field) {
            if ($field['type'] === 'boolean') {
                $parts[] = "'{$field['name']}' => \$this->{$field['name']} ?? false";
            } else {
                $parts[] = "'{$field['name']}' => \$this->{$field['name']}";
            }
        }

        return '[' . implode(', ', $parts) . ']';
    }

    protected function buildTableColumns(): string
    {
        if (empty($this->fields)) {
            return "['name']";
        }

        $columns = array_map(fn($f) => "'{$f['name']}'", $this->fields);

        return '[' . implode(', ', $columns) . ']';
    }

    protected function indexStub(): string
    {
        $model = $this->model;
        $mpl   = $this->modelPluralLower;
        $ml    = $this->modelLower;
        $columns = $this->buildTableColumns();

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{state, computed};
use Mary\Traits\Toast;

middleware(['auth', 'verified']);

state(['search' => '', 'confirmDelete' => null])->meta(['turnOff' => true]);

\$items = computed(function () {
    return {$model}::query()
        ->when(\$this->search, fn (\$q) => \$this->applySearch(\$q))
        ->latest()
        ->paginate(10);
});

protected function applySearch(\$q)
{
    // Customize search fields here
    \$q->where('name', 'like', "%{\$this->search}%");
}

\$delete = function ({$model} \${$ml}) {
    \${$ml}->delete();
    \$this->dispatch('notify', type: 'success', message: '{$model} dihapus.');
};

?>

<x-layouts.app>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{$model}</h1>
                <p class="mt-1 text-sm text-zinc-500">Kelola data {$model} di sini.</p>
            </div>
            <x-ui.button icon="plus" href="/{$mpl}/create">
                Tambah {$model}
            </x-ui.button>
        </div>

        {{-- Search & Filters --}}
        <x-ui.card :padding="false">
            <div class="p-4">
                <x-ui.input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari..."
                    icon="magnifying-glass"
                />
            </div>
        </x-ui.card>

        {{-- Table --}}
        <x-ui.card :padding="false">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <x-ui.table.th>#</x-ui.table.th>
                        <x-ui.table.th>Name</x-ui.table.th>
                        <x-ui.table.th class="text-right">Aksi</x-ui.table.th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (\$this->items as \$i => \$item)
                        <x-ui.table.row>
                            <x-ui.table.td>{{ \$this->items->firstItem() + \$i }}</x-ui.table.td>
                            <x-ui.table.td>
                                <a href="/{$mpl}/{{ \$item->id }}" wire:navigate class="font-medium hover:underline">
                                    {{ \$item->name }}
                                </a>
                            </x-ui.table.td>
                            <x-ui.table.td class="text-right">
                                <x-ui.button size="sm" variant="ghost" href="/{$mpl}/{{ \$item->id }}/edit" wire:navigate icon="pencil">
                                    Edit
                                </x-ui.button>
                                <x-ui.button size="sm" variant="danger" wire:click="\$parent->confirmDelete = {{ \$item->id }}" icon="trash">
                                    Hapus
                                </x-ui.button>
                            </x-ui.table.td>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.empty colspan="3">
                            <x-ui.empty-state icon="folder-open" title="Belum ada data" subtitle="Klik tombol 'Tambah {$model}' untuk menambah data." />
                        </x-ui.table.empty>
                    @endforelse
                </tbody>
            </table>

            @if (\$this->items->hasPages())
                <div class="border-t border-zinc-200 dark:border-zinc-700 p-4">
                    {{ \$this->items->links() }}
                </div>
            @endif
        </x-ui.card>

        {{-- Delete Confirmation Modal --}}
        <x-ui.modal title="Konfirmasi Hapus" wire:model="confirmDelete" :close-on-click-outside="false">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat diundo.
            </p>
            <x-slot name="footer">
                <x-ui.button variant="secondary" wire:click="\$set('confirmDelete', null)">Batal</x-ui.button>
                <x-ui.button variant="danger" wire:click="\$parent->delete(\$parent->confirmDelete)" :\$confirm-delete="null">
                    Ya, Hapus
                </x-ui.button>
            </x-slot>
        </x-ui.modal>

    </div>
</x-layouts.app>
BLADE;
    }

    protected function createStub(): string
    {
        $model = $this->model;
        $mpl   = $this->modelPluralLower;
        $stateFields = $this->buildStateFields();
        $validation = $this->buildValidationRules();
        $formFields = $this->buildFormFields();
        $createData = $this->buildCreateData();

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{state};

middleware(['auth', 'verified']);

state({$stateFields});

\$save = function () {
    {$validation}

    {$model}::create({$createData});

    \$this->dispatch('notify', type: 'success', message: '{$model} berhasil disimpan.');
    \$this->redirect('/{$mpl}', navigate: true);
};

?>

<x-layouts.app>
    <div class="max-w-xl space-y-6">

        {{-- Page Header --}}
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Tambah {$model} Baru</h1>
            <p class="mt-1 text-sm text-zinc-500">Isi formulir di bawah untuk menambahkan {$model} baru.</p>
        </div>

        {{-- Breadcrumb --}}
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => '/dashboard'],
            ['label' => '{$model}', 'href' => '/{$mpl}'],
            ['label' => 'Tambah'],
        ]" />

        <x-ui.card>
            <form wire:submit="save" class="space-y-4">

                {$formFields}

                <div class="flex items-center gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <x-ui.button type="submit" variant="primary" icon="check">
                        Simpan
                    </x-ui.button>
                    <x-ui.button variant="secondary" href="/{$mpl}" wire:navigate>
                        Batal
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

    </div>
</x-layouts.app>
BLADE;
    }

    protected function editStub(): string
    {
        $model = $this->model;
        $ml    = $this->modelLower;
        $mpl   = $this->modelPluralLower;
        $stateFields = $this->buildStateFields();
        $validation = $this->buildValidationRules();
        $formFields = $this->buildFormFields();
        $updateData = $this->buildUpdateData();

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{state, mount};

middleware(['auth', 'verified']);

state({$stateFields});

mount(function ({$model} \${$ml}) {
    { $this->buildMountFill() }
});

\$save = function ({$model} \${$ml}) {
    {$validation}

    \${$ml}->update({$updateData});

    \$this->dispatch('notify', type: 'success', message: '{$model} berhasil diperbarui.');
    \$this->redirect('/{$mpl}', navigate: true);
};

?>

<x-layouts.app>
    <div class="max-w-xl space-y-6">

        {{-- Page Header --}}
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit {$model}</h1>
            <p class="mt-1 text-sm text-zinc-500">Perbarui data {$model} di bawah.</p>
        </div>

        {{-- Breadcrumb --}}
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => '/dashboard'],
            ['label' => '{$model}', 'href' => '/{$mpl}'],
            ['label' => 'Edit'],
        ]" />

        <x-ui.card>
            <form wire:submit="save" class="space-y-4">

                {$formFields}

                <div class="flex items-center gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <x-ui.button type="submit" variant="primary" icon="check">
                        Update
                    </x-ui.button>
                    <x-ui.button variant="secondary" href="/{$mpl}" wire:navigate>
                        Batal
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

    </div>
</x-layouts.app>
BLADE;
    }

    protected function buildMountFill(): string
    {
        if (empty($this->fields)) {
            return "\$this->name = \${$this->modelLower}->name;";
        }

        $parts = [];
        foreach ($this->fields as $field) {
            $parts[] = "\$this->{$field['name']} = \${$this->modelLower}->{$field['name']};";
        }

        return implode("\n    ", $parts);
    }

    protected function showStub(): string
    {
        $model = $this->model;
        $ml    = $this->modelLower;
        $mpl   = $this->modelPluralLower;

        return <<<BLADE
<?php

use App\Models\\{$model};
use function Livewire\Volt\{mount};

middleware(['auth', 'verified']);

mount(function ({$model} \${$ml}) {
    //
});

?>

<x-layouts.app>
    <div class="max-w-2xl space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ \${$ml}->name }}</h1>
                <p class="mt-1 text-sm text-zinc-500">Detail {$model}.</p>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.button variant="secondary" icon="pencil" href="/{$mpl}/{{ \${$ml}->id }}/edit" wire:navigate>
                    Edit
                </x-ui.button>
                <x-ui.button variant="ghost" icon="arrow-left" href="/{$mpl}" wire:navigate>
                    Kembali
                </x-ui.button>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => '/dashboard'],
            ['label' => '{$model}', 'href' => '/{$mpl}'],
            ['label' => \${$ml}->name],
        ]" />

        <x-ui.card>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-zinc-500 uppercase tracking-wide">Nama</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ \${$ml}->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-zinc-500 uppercase tracking-wide">Dibuat</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ \${$ml}->created_at->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-zinc-500 uppercase tracking-wide">Diperbarui</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ \${$ml}->updated_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </x-ui.card>

    </div>
</x-layouts.app>
BLADE;
    }

    protected function migrationStub(): string
    {
        $table = $this->modelPluralLower;
        $columns = empty($this->fields)
            ? "\$table->string('name');"
            : implode("\n            ", array_map(
                fn($f) => $this->migrationColumn($f),
                $this->fields
            ));

        return <<<PHP
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
            {$columns}
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
PHP;
    }

    protected function migrationColumn(array $field): string
    {
        return match ($field['type']) {
            'integer', 'bigint' => "\$table->bigInteger('{$field['name']}');",
            'boolean' => "\$table->boolean('{$field['name']}')->default(false);",
            'text' => "\$table->text('{$field['name']}');",
            'email' => "\$table->string('{$field['name']}');",
            'url' => "\$table->string('{$field['name']}');",
            'date' => "\$table->date('{$field['name']}');",
            'datetime' => "\$table->dateTime('{$field['name']}');",
            'select' => "\$table->string('{$field['name']}');",
            default => "\$table->string('{$field['name']}');",
        };
    }
}