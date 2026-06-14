<?php

namespace Ilhamsyabani\VoltStarter\Abstracts;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Base User model with role support.
 *
 * Usage:
 *   class User extends VoltUser
 *   {
 *       protected string $roleColumn = 'role';
 *   }
 */
abstract class VoltUser extends Authenticatable
{
    /**
     * The role column name.
     */
    protected string $roleColumn = 'role';

    /**
     * Available roles.
     */
    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    /**
     * Get all available roles.
     */
    public static function roles(): array
    {
        return [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }

    /**
     * Check if user is superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->{$this->roleColumn} === self::ROLE_SUPERADMIN;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->{$this->roleColumn}, [self::ROLE_ADMIN, self::ROLE_SUPERADMIN]);
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->{$this->roleColumn} === self::ROLE_USER;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->{$this->roleColumn}, $roles);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return $this->hasRole($roles);
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return empty(array_diff($roles, [$this->{$this->roleColumn}]));
    }

    /**
     * Assign a role to user.
     */
    public function assignRole(string $role): void
    {
        $this->{$this->roleColumn} = $role;
        $this->save();
    }

    /**
     * Get role badge color.
     */
    public function getRoleBadgeColor(): string
    {
        return match ($this->{$this->roleColumn}) {
            self::ROLE_SUPERADMIN => 'red',
            self::ROLE_ADMIN => 'amber',
            default => 'zinc',
        };
    }

    /**
     * Get role display name.
     */
    public function getRoleDisplayName(): string
    {
        return match ($this->{$this->roleColumn}) {
            self::ROLE_SUPERADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            default => 'User',
        };
    }
}
