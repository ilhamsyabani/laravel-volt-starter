# ⚡ Laravel Volt Starter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ilhamsyabani/laravel-volt-starter.svg?style=flat-square)](https://packagist.org/packages/ilhamsyabani/laravel-volt-starter)
[![Total Downloads](https://img.shields.io/packagist/dt/ilhamsyabani/laravel-volt-starter.svg?style=flat-square)](https://packagist.org/packages/ilhamsyabani/laravel-volt-starter)
[![License](https://img.shields.io/packagist/l/ilhamsyabani/laravel-volt-starter.svg?style=flat-square)](https://img.shields.io/packagist/l/ilhamsyabani/laravel-volt-starter)

> A modern, opinionated Laravel starter kit using **Livewire Volt**, **Laravel Folio**, and **Tailwind CSS**.
> Stop repeating yourself — get a production-ready structure in minutes.

---

## ✨ What's Included

| Feature | Detail |
|---|---|
| 🧩 **Livewire Volt** | Single-file reactive components |
| 📂 **Laravel Folio** | File-based page routing |
| 🎨 **24+ UI Components** | Button, Input, Textarea, Select, Checkbox, Radio, Toggle, Badge, Card, Alert, Toast, Spinner, Modal, Tabs, Table, Pagination, Breadcrumb, Avatar, Dropdown, Empty State, Skeleton, Tooltip — 100% MIT |
| 🌈 **Theme System** | CSS-variable based — switch entire color scheme with one class (`theme-rose`, `theme-emerald`, `theme-amber`, `theme-sky`) |
| 🔐 **Auth scaffolding** | Login, register with Volt components |
| 👥 **Role system** | `superadmin`, `admin`, `user` out of the box |
| 🌙 **Dark mode** | Full dark mode with persisted preference |
| 🔔 **Toast notifications** | Global notify system, stackable |
| 📱 **Responsive sidebar** | Mobile-ready navigation with Alpine.js |
| 🛠️ **CRUD generator** | `volt-starter:crud Post --fields=title:string,body:text` |
| 📄 **Page generator** | `volt-starter:page users/index --auth` |
| 🖼️ **Component Showcase** | Built-in `/showcase` page to preview & test themes |

---

## 🎨 Theming

All components use CSS variables defined in `resources/css/volt-starter.css`. Switch the entire color scheme by adding a class to `<html>`:

```html
<html class="theme-rose">   <!-- or theme-emerald, theme-amber, theme-sky -->
```

Or define your own theme:

```css
.theme-custom {
  --vs-primary-500: 168 85 247; /* your RGB values */
  --vs-primary-600: 147 51 234;
  --vs-primary-700: 126 34 206;
  /* ... */
}
```

No paid component library required — everything is plain Tailwind + Blade.

---

## 🚀 Installation

```bash
composer require ilhamsyabani/laravel-volt-starter
```

Then run the install command:

```bash
# Basic install (layouts + dashboard)
php artisan volt-starter:install

# With authentication scaffolding
php artisan volt-starter:install --auth

# With auth + role system
php artisan volt-starter:install --auth --roles

# Install everything
php artisan volt-starter:install --full
```

---

## ⚙️ Requirements

- PHP ^8.2
- Laravel ^11.0 or ^12.0
- [Livewire Volt](https://livewire.laravel.com/docs/volt) ^1.0
- [Laravel Folio](https://github.com/laravel/folio) ^1.0
- Tailwind CSS (via Vite) — already in fresh Laravel installs

---

## 🛠️ Generator Commands

### Generate a Volt + Folio page

```bash
# Creates resources/views/pages/products/index.blade.php
php artisan volt-starter:page products/index --auth

# Admin-only page
php artisan volt-starter:page admin/dashboard --admin

# Superadmin-only page
php artisan volt-starter:page admin/users --superadmin

# Bare minimum (no boilerplate)
php artisan volt-starter:page reports/monthly --bare
```

### Generate full CRUD pages

```bash
# Basic CRUD for a Post model
php artisan volt-starter:crud Post

# With specific fields
php artisan volt-starter:crud Product --fields=title:string,description:text,price:integer,status:select:pending|active

# With migration
php artisan volt-starter:crud Post --fields=title:string,body:text --with-migration
```

This generates:
```
resources/views/pages/
└── posts/
    ├── index.blade.php    → GET /posts
    ├── create.blade.php   → GET /posts/create
    └── [post]/
        ├── edit.blade.php → GET /posts/{post}/edit
        └── show.blade.php → GET /posts/{post}
```

---

## 📁 Published File Structure

After running `volt-starter:install --full`:

```
resources/
├── css/
│   └── volt-starter.css           # Theme CSS variables
├── views/
│   ├── components/
│   │   ├── layouts/
│   │   │   ├── app.blade.php      # Main layout wrapper
│   │   │   └── app/
│   │   │       └── sidebar.blade.php
│   │   └── ui/                     # 24+ UI components
│   │       ├── button.blade.php
│   │       ├── input.blade.php
│   │       ├── card.blade.php
│   │       ├── table/
│   │       │   ├── th.blade.php
│   │       │   ├── td.blade.php
│   │       │   ├── row.blade.php
│   │       │   └── empty.blade.php
│   │       └── ...
│   └── pages/
│       ├── dashboard.blade.php
│       ├── showcase.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       └── settings/
│           └── profile.blade.php
routes/
└── folio.php                       # Folio route configuration
app/Http/Middleware/
├── EnsureUserHasRole.php
├── EnsureUserIsOwner.php
└── EnsureUserHasPermission.php
```

---

## 🔔 Toast Notification System

Available everywhere via Livewire dispatch:

```php
// In any Volt component
$this->dispatch('notify', type: 'success', message: 'Saved!');
$this->dispatch('notify', type: 'error',   message: 'Something went wrong.');
$this->dispatch('notify', type: 'warning', message: 'Please check your input.');
$this->dispatch('notify', type: 'info',    message: 'Processing...');
```

---

## 👥 Role System

When installed with `--roles`, a `role` column is added to `users` table:

```php
// In any Volt component or middleware
auth()->user()->role // 'superadmin' | 'admin' | 'user'

// Helper trait (included)
use Ilhamsyabani\VoltStarter\Traits\HasRoles;

$user->isAdmin();       // true if admin or superadmin
$user->isSuperAdmin();  // true if superadmin
$user->isUser();        // true if user
$user->hasRole('admin'); // check specific role
```

### Middleware Usage

```php
// In routes/folio.php or bootstrap/app.php
Route::middleware(['auth', 'verified', 'role:admin'])->group(fn () => Folio::route('/admin'));
```

---

## 📂 Laravel Folio Integration

Folio enables file-based routing. Every file in `resources/views/pages/` becomes a route automatically.

### Route Patterns

| File Path | Route |
|---|---|
| `pages/index.blade.php` | `GET /` |
| `pages/users.blade.php` | `GET /users` |
| `pages/users/index.blade.php` | `GET /users` |
| `pages/users/create.blade.php` | `GET /users/create` |
| `pages/posts/[post]/edit.blade.php` | `GET /posts/{post}/edit` |

### Middleware in Pages

Add middleware directly in the page file:

```php
<?php

use function Livewire\Volt\{state};

middleware(['auth', 'verified']);        // Auth required
middleware(['auth', 'role:admin']);      // Admin only
middleware(['auth', 'role:superadmin']); // Superadmin only

state(['name' => '']);

?>
```

---

## 🧩 UI Components

### Button

```blade
<x-ui.button variant="primary" size="md" icon="plus">
    Add New
</x-ui.button>

<x-ui.button variant="danger" size="sm" icon="trash" loading>
    Delete
</x-ui.button>

<x-ui.button href="/dashboard" variant="ghost" iconTrailing="arrow-right">
    Continue
</x-ui.button>
```

### Input

```blade
<x-ui.input
    wire:model="email"
    label="Email Address"
    placeholder="you@example.com"
    icon="envelope"
    required
/>
```

### Card

```blade
<x-ui.card title="Statistics" subtitle="Monthly overview">
    <p>Card content here</p>

    <x-slot:actions>
        <x-ui.button size="sm">View All</x-ui.button>
    </x-slot:actions>

    <x-slot:footer>
        Footer content
    </x-slot:footer>
</x-ui.card>
```

### Table

```blade
<x-ui.table>
    <x-slot:head>
        <x-ui.table.th>Name</x-ui.table.th>
        <x-ui.table.th>Email</x-ui.table.th>
        <x-ui.table.th align="right">Actions</x-ui.table.th>
    </x-slot:head>

    @forelse ($users as $user)
        <x-ui.table.row>
            <x-ui.table.td>{{ $user->name }}</x-ui.table.td>
            <x-ui.table.td>{{ $user->email }}</x-ui.table.td>
            <x-ui.table.td align="right">
                <x-ui.button size="sm" variant="ghost">Edit</x-ui.button>
            </x-ui.table.td>
        </x-ui.table.row>
    @empty
        <x-ui.table.empty colspan="3">
            No users found.
        </x-ui.table.empty>
    @endforelse
</x-ui.table>
```

### Modal

```blade
{{-- Trigger button --}}
<x-ui.button @click="$dispatch('open-modal', { name: 'confirm-delete' })">
    Delete
</x-ui.button>

{{-- Modal component --}}
<x-ui.modal name="confirm-delete" title="Confirm Delete">
    <p>Are you sure?</p>

    <x-slot:footer>
        <x-ui.button variant="secondary" @click="$dispatch('close-modal', { name: 'confirm-delete' })">
            Cancel
        </x-ui.button>
        <x-ui.button variant="danger" wire:click="delete">
            Delete
        </x-ui.button>
    </x-slot:footer>
</x-ui.modal>
```

### Breadcrumb

```blade
<x-ui.breadcrumb :items="[
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Posts', 'href' => '/posts'],
    ['label' => 'Edit'],
]" />
```

### Alert

```blade
<x-ui.alert type="success" title="Success!" dismissible>
    Your changes have been saved.
</x-ui.alert>

<x-ui.alert type="error" title="Error">
    Something went wrong.
</x-ui.alert>
```

---

## 📚 Belajar Volt + Folio

### Konsep Dasar Volt

| Fitur | Penjelasan | Contoh |
|---|---|---|
| `state()` | Reactive state | `state(['name' => ''])` |
| `computed()` | Computed property | `computed(fn() => User::all())` |
| `mount()` | Initial load | `mount(fn() => $this->loadData())` |
| `rules()` | Validation | `rules(['email' => 'required\|email'])` |

### Contoh Volt Component

```php
<?php

use App\Models\User;
use function Livewire\Volt\{state, computed};

state(['search' => '']);

$users = computed(function () {
    return User::query()
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
        ->paginate(10);
});

$delete = function (User $user) {
    $user->delete();
    $this->dispatch('notify', type: 'success', message: 'User deleted.');
};

?>

<x-layouts.app>
    <!-- Template here -->
</x-layouts.app>
```

### Contoh Folio Routing

```php
// routes/folio.php
use Illuminate\Support\Facades\Route;
use Laravel\Folio\Folio;

// Load all pages from resources/views/pages/
Folio::route(resource_path('views/pages'));

// Protected routes (sub-folder mapping)
Route::middleware(['auth', 'verified'])->group(fn () => Folio::route(resource_path('views/pages/dashboard'), uri: '/dashboard'));

// Admin routes (sub-folder mapping)
Route::middleware(['auth', 'role:admin'])->group(fn () => Folio::route(resource_path('views/pages/admin'), uri: '/admin'));
```

---

## 🤝 Contributing

Pull requests are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) first.

```bash
git clone https://github.com/ilhamsyabani/laravel-volt-starter
cd laravel-volt-starter
composer install
./vendor/bin/pest
```

---

## 📄 License

MIT — see [LICENSE](LICENSE.md)

---

<p align="center">Built with ❤️ by <a href="https://github.com/ilhamsyabani">ilhamsyabani</a> · Indonesia 🇮🇩</p>
