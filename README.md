# ⚡ Laravel Volt Starter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ilhamsyabani/laravel-volt-starter.svg?style=flat-square)](https://packagist.org/packages/ilhamsyabani/laravel-volt-starter)
[![Total Downloads](https://img.shields.io/packagist/dt/ilhamsyabani/laravel-volt-starter.svg?style=flat-square)](https://packagist.org/packages/ilhamsyabani/laravel-volt-starter)
[![License](https://img.shields.io/packagist/l/ilhamsyabani/laravel-volt-starter.svg?style=flat-square)](https://packagist.org/packages/ilhamsyabani/laravel-volt-starter)

> A modern, opinionated Laravel starter kit using **Livewire Volt**, **Laravel Folio**, **Flux UI**, and **Tailwind CSS**.  
> Stop repeating yourself — get a production-ready structure in minutes.

---

## ✨ What's Included

| Feature | Detail |
|---|---|
| 🧩 **Livewire Volt** | Single-file reactive components |
| 📂 **Laravel Folio** | File-based page routing |
| 🎨 **20 UI Components** | Tier 1 + Tier 2 — Button, Input, Textarea, Select, Checkbox, Radio, Toggle, Badge, Card, Alert, Toast, Spinner, Modal, Dropdown, Table, Pagination, Tabs, Breadcrumb, Avatar, Tooltip, Skeleton, Empty State — 100% MIT, no license needed |
| 🌈 **Theme System** | CSS-variable based — switch entire color scheme with one class (`theme-rose`, `theme-emerald`, `theme-amber`, `theme-sky`, or build your own) |
| 🔐 **Auth scaffolding** | Login, register, email verify, password reset |
| 👥 **Role system** | `superadmin`, `admin`, `user` out of the box |
| 🌙 **Dark mode** | Full dark mode with persisted preference |
| 🔔 **Toast notifications** | Global notify system, stackable |
| 📱 **Responsive sidebar** | Mobile-ready navigation with Alpine.js |
| 🛠️ **CRUD generator** | `volt-starter:crud Post` and you're done |
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

# Bare minimum (no boilerplate)
php artisan volt-starter:page reports/monthly --bare
```

### Generate full CRUD pages

```bash
# Basic CRUD for a Post model
php artisan volt-starter:crud Post

# With specific fields
php artisan volt-starter:crud Product --fields=name:string,description:text,price:string,stock:integer
```

This generates:
```
resources/views/pages/
└── products/
    ├── index.blade.php    → GET /products
    ├── create.blade.php   → GET /products/create
    └── [product]/
        └── edit.blade.php → GET /products/{product}/edit
```

---

## 📁 Published File Structure

After running `volt-starter:install --auth --roles`:

```
resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php          # Main layout wrapper
│   │   └── app/
│   │       └── sidebar.blade.php  # Navigation + toast system
│   └── ui/
│       ├── button.blade.php       # Extended button component
│       └── badge.blade.php        # Role/status badge
└── pages/
    ├── dashboard.blade.php        # Main dashboard
    ├── auth/
    │   ├── login.blade.php
    │   ├── register.blade.php
    │   └── forgot-password.blade.php
    └── settings/
        └── profile.blade.php
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
```

---

## 💡 Why This Package?

Laravel already ships with Breeze and Jetstream — but neither of them uses the modern **Volt + Folio** approach. If you've adopted Livewire Volt's single-file component style and Laravel Folio's file-based routing, you know there's no starter kit for this combination.

This package fills that gap.

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
