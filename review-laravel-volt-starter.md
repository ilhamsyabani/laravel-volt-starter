# Code Review: `laravel-volt-starter`
**Reviewer:** Senior Developer  
**Target Stack:** Laravel 13 · Livewire 4 · Volt · Laravel Folio  
**Tanggal:** 16 Juni 2026

---

## Ringkasan Eksekutif

Package ini punya fondasi yang **bagus dan terstruktur** — arsitektur service provider bersih, trait reusable, CRUD generator, dan 20+ komponen UI. Namun ada beberapa isu **kritis** yang perlu diperbaiki sebelum dipakai di production, terutama seputar kompatibilitas Volt 4, duplikasi kode, dan inkonsistensi konfigurasi.

---

## 🔴 Critical Issues (harus diperbaiki)

### 1. Versi Volt di `composer.json` Kontradiksi


```json
// require-dev ✅
"livewire/volt": "^1.10"

// suggest ❌ — outdated
"livewire/volt": "Required for Volt components (^1.1)"
```

Dan `composer.lock` masih terkunci di `v1.10.5`. Artinya package ini **belum benar-benar diuji dengan Volt 4**. Ini harus dibersihkan dan lock file di-regenerate dengan Volt 4.

**Fix:**
```json
"suggest": {
    "livewire/livewire": "Required for Volt components (^4.0)",
    "livewire/volt": "Required for single-file Volt components (^1.10)",
    "laravel/folio": "Required for file-based routing (^1.0)"
}
```

---

### 2. `@volt` Directive di Blade Tidak Valid di Volt 4

Di `stubs/pages/auth/login.blade.php` (dan kemungkinan page lain):

```blade
{{-- ❌ Ini syntax Volt 1.x / Livewire 2 --}}
@volt($login)
    ...
@endvolt
```

Di Volt 4 (yang bundled dengan Livewire 3+), file `.blade.php` dalam folder `pages/` **sudah secara otomatis dianggap sebagai Volt component** ketika di-mount via `Volt::mount()`. Tidak ada `@volt` wrapper lagi. Logic PHP ditulis di blok `<?php ... ?>` di atas file, dan Blade template langsung mengikuti.

**Fix login.blade.php:**
```blade
<?php
// Hapus @volt wrapper, logic tetap di atas
use Illuminate\Support\Facades\Auth;
use function Laravel\Folio\{middleware};
use function Livewire\Volt\{state, rules};

middleware(['guest']);

state(['email' => '', 'password' => '', 'remember' => false]);
rules(['email' => 'required|email', 'password' => 'required|string']);

$login = function () {
    $this->validate();
    if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
        throw \Illuminate\Validation\ValidationException::withMessages(['email' => __('auth.failed')]);
    }
    session()->regenerate();
    $this->redirectIntended('/dashboard', navigate: true);
};
?>

<x-layouts.auth title="Sign in">
    {{-- Langsung template, TANPA @volt wrapper --}}
    <div class="w-full max-w-sm mx-auto">
        ...
    </div>
</x-layouts.auth>
```

---

### 3. `FolioServiceProvider` Tidak Pernah Didaftarkan

File `src/FolioServiceProvider.php` ada, tapi **tidak pernah di-register** di `VoltStarterServiceProvider.php` maupun di `extra.laravel.providers` di `composer.json`. Artinya Folio routing dari package ini tidak akan pernah aktif secara otomatis.

```php
// VoltStarterServiceProvider.php — FolioServiceProvider tidak ada di sini
```

**Fix** — daftarkan di `configurePackage()` atau di `packageBooted()`:
```php
// Di VoltStarterServiceProvider.php
public function packageBooted(): void
{
    $this->app->register(FolioServiceProvider::class);
    // ...
}
```

Atau tambahkan ke `composer.json`:
```json
"extra": {
    "laravel": {
        "providers": [
            "Ilhamsyabani\\VoltStarter\\VoltStarterServiceProvider",
            "Ilhamsyabani\\VoltStarter\\FolioServiceProvider"
        ]
    }
}
```

---

### 4. `VoltCrud` Abstracts Tidak Kompatibel dengan Volt Component

Di `VoltCrudCreate` dan `VoltCrudEdit`:

```php
// ❌ $this->all() tidak ada di Volt component
$this->model::create($this->all());

// ❌ $this->validate($rules) — di Volt 4 pakai validate() tanpa argumen
$this->validate($this->rules);

// ❌ $this->redirectRoute() — method ini ada tapi perlu dipastikan
$this->redirectRoute($this->getRedirectRoute(), navigate: true);
```

Di Volt 4, state diakses langsung via property (`$this->title`), bukan via `$this->all()`. Untuk ambil semua state, harus eksplisit.

**Fix `VoltCrudCreate::store()`:**
```php
public function store(array $data): void
{
    // $data dioper dari component child yang tahu field-nya
    $validated = validator($data, $this->rules)->validate();
    $this->model::create($validated);
    $this->success('Item created successfully.');
    $this->redirect(route($this->getRedirectRoute()), navigate: true);
}
```

Atau lebih baik, dokumentasikan bahwa class ini hanya blueprint — developer wajib override `store()` dan `update()` di component mereka sendiri.

---

## 🟡 Medium Issues (sangat direkomendasikan untuk diperbaiki)

### 5. Duplikasi Logika Roles: `HasRoles` Trait vs `VoltUser` Abstract

Dua implementasi roles yang hampir identik tapi **tidak konsisten**:

| Method | `HasRoles` Trait | `VoltUser` Abstract |
|--------|-----------------|---------------------|
| `hasRole(string)` | Hierarchy-based (int comparison) | `in_array` |
| `hasRole(array)` | ❌ Tidak support | ✅ Support |
| `hasAnyRole()` | ❌ Tidak ada | ✅ Ada |
| `hasAllRoles()` | ❌ Tidak ada | ✅ Ada |
| `assignRole()` | ❌ Tidak ada | ✅ Ada |
| `authorizeRole()` | ✅ Ada | ❌ Tidak ada |

Ini akan membingungkan developer: pakai yang mana? Dan jika User extend `VoltUser` tapi juga `use HasRoles`, ada method conflict.

**Rekomendasi:** Hapus `HasRoles` trait, jadikan `VoltUser` sebagai satu-satunya sumber kebenaran. Atau sebaliknya — jadikan `VoltUser` hanya extend `Authenticatable + use HasRoles` agar DRY.

---

### 6. Dua Config File yang Tidak Sinkron

`config/laravel-volt-starter.php` (config package) hanya punya 3 key:
```php
'theme', 'roles', 'default_role'
```

Sedangkan `stubs/config/volt-starter.php` (yang dipublish ke app) punya 8+ key:
```php
'theme', 'layout', 'sidebar', 'pagination', 'toast', 'middleware', 'roles', 'dark_mode', 'api'
```

Ini **tidak sinkron**. Jika package code memanggil `config('laravel-volt-starter.toast')`, hasilnya `null` karena key itu hanya ada di stub.

**Fix:** Satukan atau buat konfig package sebagai fallback dari konfig stub.

---

### 7. `Sortable` Trait — `sortBy()` Bisa Gagal Silent

```php
public function sortBy(string $field): void
{
    if ($this->sortField === $field) { // ❌ undefined property jika lupa deklarasi state
        $this->sortDirection = ...;
    }
}
```

Jika developer lupa mendeklarasikan `state(['sortField' => 'created_at', 'sortDirection' => 'desc'])` di component mereka, ini akan throw error yang tidak jelas.

**Fix:** Tambahkan null coalescing guard atau dokumentasikan lebih tegas di docblock bahwa state wajib dideklarasikan:
```php
public function sortBy(string $field): void
{
    $currentField = $this->sortField ?? 'created_at';
    $currentDir   = $this->sortDirection ?? 'desc';

    if ($currentField === $field) {
        $this->sortDirection = $currentDir === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField     = $field;
        $this->sortDirection = 'desc';
    }
}
```

---

### 8. `registerFolioInBootstrap()` — String Replace Rapuh

```php
$search  = "web: __DIR__.'/../routes/web.php',";
$replace = "web: __DIR__.'/../routes/web.php',\n            then: function () { ... }";
```

Di Laravel 13, format `bootstrap/app.php` bisa sedikit berbeda (trailing comma, whitespace, dll). Jika string exact match gagal, fallback-nya inject ke `web.php` — tapi itu juga fragile karena tidak check apakah sudah ada `<?php` header atau tidak.

**Rekomendasi:** Publish stub `bootstrap/app.php` langsung (sudah ada di `stubs/bootstrap/app.php`) dan minta user backup dulu, daripada mencoba string-replace file yang ada.

---

## 🟢 Minor Issues & Saran Perbaikan

### 9. `getSortIcon()` Return Value Identik

```php
// ❌ Kedua branch return string yang sama
return $this->sortDirection === 'asc'
    ? 'vs-text-primary'   // asc
    : 'vs-text-primary';  // desc
```

Ini bug — harusnya ada perbedaan icon/class untuk asc vs desc.

---

### 10. `checkDependencies()` Cek `blade-phosphor-icons` Tapi Tidak Ada di `require`

```php
if (! class_exists(\Codeat3\BladePhosphorIcons\BladePhosphorIconsServiceProvider::class)) {
    $missing[] = 'codeat3/blade-phosphor-icons';
}
```

Paket `codeat3/blade-phosphor-icons` tidak ada di `require` atau `suggest` di `composer.json`. Developer akan confused kenapa dependency ini diminta tapi tidak muncul di docs.

**Fix:** Tambahkan ke `suggest`:
```json
"suggest": {
    "codeat3/blade-phosphor-icons": "Required for icon components"
}
```

---

### 11. `PresetCommand` — Presets 'saas' dan 'api' Tidak Ada Stub-nya

Di `stubs/presets/` hanya ada folder `blog/` dan `dashboard/`. Tapi `PresetCommand` menyebutkan preset `saas` dan `api`. Kalau user jalankan `volt-starter:preset saas`, kemungkinan besar akan error.

---

### 12. `MakeCrudCommand` Tidak Generate Show Page

Di `handle()` hanya generate `index`, `create`, `edit`. Tidak ada `show`. Ini mungkin by-design tapi perlu didokumentasikan karena nama commandnya adalah `make:crud` yang biasanya full CRUD.

---

## ✅ Yang Sudah Baik

- Struktur package menggunakan `spatie/laravel-package-tools` — ini best practice.
- Publish tags dibagi per concern (`volt-starter-layouts`, `volt-starter-auth`, dll) — sangat bagus untuk selective install.
- `Toast` trait clean dan mudah digunakan.
- Folio middleware pattern di `routes/folio.php` sudah benar.
- `VoltUser::hasAllRoles()` implementasinya ada sedikit bug logika (user hanya bisa punya 1 role, tapi method check multiple), tapi konsepnya ada.
- CI/CD via GitHub Actions sudah ada.
- Orchesta Testbench support `^9.0|^10.0|^11.0` — good future-proofing.

---

## Checklist Prioritas Perbaikan

| # | Issue | Severity | Estimasi |
|---|-------|----------|----------|
| 1 | Perbaiki versi Volt di `suggest` + regenerate `composer.lock` | 🔴 Critical | 15 menit |
| 2 | Hapus `@volt`/`@endvolt` dari semua blade stubs | 🔴 Critical | 30 menit |
| 3 | Daftarkan `FolioServiceProvider` | 🔴 Critical | 10 menit |
| 4 | Fix/dokumentasikan `VoltCrud::store()` dan `update()` | 🔴 Critical | 1 jam |
| 5 | Merge duplikasi `HasRoles` vs `VoltUser` | 🟡 Medium | 45 menit |
| 6 | Sinkronkan dua config file | 🟡 Medium | 30 menit |
| 7 | Guard null di `Sortable::sortBy()` | 🟡 Medium | 15 menit |
| 8 | Ganti regex string-replace bootstrap dengan publish stub | 🟡 Medium | 30 menit |
| 9 | Fix `getSortIcon()` return value | 🟢 Minor | 5 menit |
| 10 | Tambah `blade-phosphor-icons` ke `suggest` | 🟢 Minor | 5 menit |
| 11 | Hapus/implementasikan preset `saas` & `api` | 🟢 Minor | 1 jam |

---

*Total estimasi perbaikan critical+medium: ~3.5 jam kerja*
