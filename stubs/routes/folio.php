<?php

use Illuminate\Support\Facades\Route;
use Laravel\Folio\Folio;

/*
|--------------------------------------------------------------------------
| Folio Route Configuration
|--------------------------------------------------------------------------
|
| Konfigurasi route untuk Laravel Folio.
| Setiap file di resources/views/pages/ akan otomatis jadi route.
|
| Route Pattern:
|   pages/users/index.blade.php         → GET /users
|   pages/users/create.blade.php       → GET /users/create
|   pages/posts/[post]/edit.blade.php  → GET /posts/{post}/edit
|   pages/posts/[post]/show.blade.php   → GET /posts/{post}
|
| Middleware di dalam file page:
|   middleware(['auth', 'verified']);
|
| Atau gunakan group middleware di sini:
|   Route::middleware(['auth'])->group(fn () => Folio::route(resource_path('views/pages/dashboard'), uri: '/dashboard'));
|
*/

// Home / Landing Page
Folio::route(resource_path('views/pages'));

// Dashboard (protected)
Route::middleware(['auth', 'verified'])->group(fn () => Folio::route(resource_path('views/pages/dashboard'), uri: '/dashboard'));

// Authentication Pages
Route::middleware(['guest'])->group(fn () => Folio::route(resource_path('views/pages/auth'), uri: '/auth'));

// Settings (protected)
Route::middleware(['auth', 'verified'])->group(fn () => Folio::route(resource_path('views/pages/settings'), uri: '/settings'));

// Admin Pages (role-based)
Route::middleware(['auth', 'verified', 'role:admin'])->group(fn () => Folio::route(resource_path('views/pages/admin'), uri: '/admin'));

// API Routes (exclude from Folio)
Route::prefix('api')->group(base_path('routes/api.php'));
