# Changelog

All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- `authorizeRole()` method to VoltUser abstract for role authorization
- `assignRole()` method to VoltUser abstract for role assignment

### Changed
- **Improved Role Hierarchy**: Hierarchical role checking now supports inheritance (admin can access user permissions, superadmin can access all)
- **Command Registration**: Commands now only register in console context for better performance
- **Folio Registration**: Folio routing now handled manually via `php artisan volt-starter:install --folio`

---

## [v1.3.2] - 2026-06-17

### Fixed
- Add `@volt` directive to all Folio pages
- Add auth layout for login and register pages
- Add missing `@volt` directives to Folio pages

### Changed
- Updated composer.json dependencies
- Improved VoltUser and HasRoles trait consistency

---

## [v1.3.1] - 2026-06-16

### Fixed
- Add `@volt` directive to all Folio pages
- Add auth layout for consistency

---

## [v1.3.0] - 2026-06-16

### Added
- Complete configuration file (`config/laravel-volt-starter.php`) with comprehensive options:
  - Theme configuration (default, rose, emerald, amber, sky)
  - Layout configuration
  - Sidebar configuration
  - Pagination settings
  - Toast notification settings
  - Middleware configuration
  - Role hierarchy settings
  - Dark mode settings
  - API configuration
- Role hierarchy in config (`roles` array in order of hierarchy)

### Fixed
- Add `@volt` directive to all Folio pages
- Icon compatibility with Phosphor Icons
- Folio page structure for proper Volt integration

### Changed
- Refactored VoltUser abstract to use config-based roles
- Improved HasRoles trait with better hierarchy support
- Sortable trait icon return value fixed
- PresetCommand now properly validates preset availability

---

## [v1.2.0] - 2026-06-15

### Added
- Auto-setup files for quick project initialization
- Support for Laravel 13

### Changed
- Moved Livewire from `require` to `suggest` in composer.json
- Removed version field from composer.json
- Simplified CI workflow

### Fixed
- Various Composer configuration issues for Laravel 13 compatibility

---

## [v1.1.0] - 2026-06-14

### Changed
- Simplified CI workflow
- Removed composer.lock from version control
- Upgraded GitHub Actions to latest versions

### Fixed
- Service provider command registration
- Test coverage improvements

---

## [v1.0.0] - 2026-06-13

### Added
- Initial release
- Laravel Volt Starter package foundation
- Volt and Folio integration
- User authentication pages (login, register)
- Role-based access control
- User management with role assignment
- Profile settings page
- 20+ UI components:
  - Alert, Avatar, Badge, Breadcrumb, Button, Card, Checkbox, Dropdown,
    Empty State, Input, Modal, Pagination, Radio, Select, Skeleton, Spinner,
    Table, Tabs, Textarea, Toast, Toggle, Tooltip
- Dashboard page with statistics
- Blog preset (posts, categories, tags, comments)
- Dashboard preset (users, roles, analytics)
- Artisan commands:
  - `volt-starter:install` - Install Volt and Folio
  - `volt-starter:page` - Generate new page
  - `volt-starter:preset` - Install preset templates
  - `volt-starter:make-crud` - Generate CRUD pages
- Traits: HasRoles, Searchable, Sortable, Toast
- Volt Crud Abstracts for rapid CRUD development
- VoltUser abstract class with built-in role management
- Dark mode support
- Multi-theme support (Indigo, Rose, Emerald, Amber, Sky)
- Comprehensive Tailwind CSS configuration
- GitHub Actions CI/CD workflow
