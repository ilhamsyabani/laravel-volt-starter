<?php

namespace Ilhamsyabani\VoltStarter\Support;

use Livewire\Volt\Volt;

if (!function_exists('volt_function')) {
    /**
     * Register a named function in Volt.
     *
     * Usage in Volt page:
     *   volt_function('greet', function($name) {
     *       return "Hello, {$name}!";
     *   });
     *
     * Call from template:
     *   {{ $this->greet('World') }}
     */
    function volt_function(string $name, callable $callback): void
    {
        Volt::namedFunction($name, $callback);
    }
}

if (!function_exists('notify')) {
    /**
     * Dispatch a toast notification.
     *
     * Usage:
     *   notify('success', 'Data saved!');
     *   notify('error', 'Something went wrong.');
     *   notify('warning', 'Please check your input.');
     *   notify('info', 'Processing...');
     */
    function notify(string $type, string $message): void
    {
        $validTypes = ['success', 'error', 'warning', 'info'];

        if (!in_array($type, $validTypes)) {
            $type = 'info';
        }

        // In Livewire context
        if (function_exists('dispatch')) {
            dispatch('notify', type: $type, message: $message);
        }
    }
}

if (!function_exists('notify_success')) {
    /**
     * Dispatch a success toast notification.
     */
    function notify_success(string $message): void
    {
        notify('success', $message);
    }
}

if (!function_exists('notify_error')) {
    /**
     * Dispatch an error toast notification.
     */
    function notify_error(string $message): void
    {
        notify('error', $message);
    }
}

if (!function_exists('notify_warning')) {
    /**
     * Dispatch a warning toast notification.
     */
    function notify_warning(string $message): void
    {
        notify('warning', $message);
    }
}

if (!function_exists('notify_info')) {
    /**
     * Dispatch an info toast notification.
     */
    function notify_info(string $message): void
    {
        notify('info', $message);
    }
}

if (!function_exists('breadcrumb')) {
    /**
     * Generate breadcrumb items array.
     *
     * Usage:
     *   breadcrumb([
     *       ['label' => 'Home', 'href' => '/'],
     *       ['label' => 'Posts', 'href' => '/posts'],
     *       ['label' => 'Edit'],
     *   ])
     */
    function breadcrumb(array $items): array
    {
        return $items;
    }
}

if (!function_exists('page_title')) {
    /**
     * Set page title with optional suffix.
     *
     * Usage:
     *   page_title('Dashboard'); // "Dashboard — App Name"
     *   page_title('Edit Post', 'App Name'); // "Edit Post — App Name"
     */
    function page_title(string $title, ?string $appName = null): string
    {
        $appName = $appName ?? config('app.name', 'Laravel');
        return "{$title} — {$appName}";
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date with common formats.
     *
     * Usage:
     *   format_date($date); // "14 Jun 2026"
     *   format_date($date, 'full'); // "June 14, 2026"
     *   format_date($date, 'short'); // "Jun 14"
     */
    function format_date($date, string $format = 'default'): string
    {
        if (!$date) {
            return '-';
        }

        $date = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        return match ($format) {
            'full' => $date->format('F j, Y'),
            'short' => $date->format('M j'),
            'time' => $date->format('H:i'),
            'datetime' => $date->format('M j, Y H:i'),
            'relative' => $date->diffForHumans(),
            default => $date->format('j M Y'),
        };
    }
}

if (!function_exists('truncate_words')) {
    /**
     * Truncate text to specified word count.
     */
    function truncate_words(string $text, int $words = 25, string $end = '...'): string
    {
        $text = strip_tags($text);
        $wordsArray = explode(' ', $text, $words + 1);

        if (count($wordsArray) > $words) {
            array_pop($wordsArray);
            return implode(' ', $wordsArray) . $end;
        }

        return $text;
    }
}

if (!function_exists('active_route')) {
    /**
     * Check if current route matches pattern.
     *
     * Usage:
     *   active_route('dashboard'); // true if on dashboard
     *   active_route('users.*'); // true if on any users route
     */
    function active_route(string $pattern): bool
    {
        $current = request()->route()?->getName() ?? request()->path();

        if (str_contains($pattern, '*')) {
            $pattern = str_replace('*', '.*', $pattern);
            return (bool) preg_match("/^{$pattern}$/", $current);
        }

        return $current === $pattern || str_contains(request()->path(), $pattern);
    }
}

if (!function_exists('is_active_nav')) {
    /**
     * Return 'active' class if route matches.
     *
     * Usage in Blade:
     *   <a class="{{ is_active_nav('dashboard') }}">Dashboard</a>
     */
    function is_active_nav(string $pattern): string
    {
        return active_route($pattern) ? 'vs-bg-primary-light vs-text-primary' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800';
    }
}

if (!function_exists('user_avatar')) {
    /**
     * Generate user avatar initials or image.
     *
     * Usage:
     *   user_avatar($user); // Returns initials or image URL
     */
    function user_avatar($user): string
    {
        if (!$user) {
            return 'G';
        }

        // If user has avatar, return it
        if (!empty($user->avatar)) {
            return asset('storage/' . $user->avatar);
        }

        // Return initials
        return strtoupper(substr($user->name ?? 'G', 0, 1));
    }
}

if (!function_exists('money_format')) {
    /**
     * Format number as currency.
     *
     * Usage:
     *   money_format(1000000); // "Rp 1.000.000"
     *   money_format(1000, 'USD'); // "$1,000.00"
     */
    function money_format(float $amount, string $currency = 'IDR'): string
    {
        return match (strtoupper($currency)) {
            'IDR' => 'Rp ' . number_format($amount, 0, ',', '.'),
            'USD' => '$' . number_format($amount, 2, '.', ','),
            'EUR' => '€' . number_format($amount, 2, ',', '.'),
            default => number_format($amount, 2, '.', ','),
        };
    }
}

if (!function_exists('status_badge')) {
    /**
     * Generate status badge classes.
     *
     * Usage:
     *   status_badge('active'); // ['bg-green-100', 'text-green-800']
     */
    function status_badge(string $status): array
    {
        return match ($status) {
            'active', 'published', 'completed', 'success', 'approved' => [
                'bg' => 'bg-emerald-100 dark:bg-emerald-900/30',
                'text' => 'text-emerald-700 dark:text-emerald-400',
            ],
            'pending', 'draft', 'processing', 'waiting' => [
                'bg' => 'bg-amber-100 dark:bg-amber-900/30',
                'text' => 'text-amber-700 dark:text-amber-400',
            ],
            'inactive', 'unpublished', 'cancelled', 'failed', 'rejected' => [
                'bg' => 'bg-red-100 dark:bg-red-900/30',
                'text' => 'text-red-700 dark:text-red-400',
            ],
            'archived' => [
                'bg' => 'bg-zinc-100 dark:bg-zinc-800',
                'text' => 'text-zinc-600 dark:text-zinc-400',
            ],
            default => [
                'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                'text' => 'text-blue-700 dark:text-blue-400',
            ],
        };
    }
}
