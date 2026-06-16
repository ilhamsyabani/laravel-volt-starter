<?php

namespace Ilhamsyabani\VoltStarter\Abstracts;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Base User model with role support.
 *
 * This abstract class provides a complete user model with role management.
 * For projects that already have their own User model, use the HasRoles trait instead.
 *
 * Usage:
 *   class User extends VoltUser
 *   {
 *       protected string $roleColumn = 'role'; // default
 *   }
 *
 * Role Hierarchy (lowest to highest):
 *   user < admin < superadmin
 */
abstract class VoltUser extends Authenticatable
{
    /**
     * The role column name.
     */
    protected string $roleColumn = 'role';

    /**
     * Available roles in order of hierarchy.
     */
    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    /**
     * Get all available roles.
     * Uses config to allow customization without modifying the model.
     */
    public static function roles(): array
    {
        return config('laravel-volt-starter.roles', [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ]);
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
     *
     * @param string|array $roles Single role or array of roles
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
        return $this->hasRole($roles);
    }

    /**
     * Check if user has all of the given roles.
     * Note: A user typically has only one role, so this returns true only if
     * the given roles array contains exactly the user's current role.
     */
    public function hasAllRoles(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->{$this->roleColumn}, $roles) && count($roles) === 1;
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
     * Abort with 403 if user doesn't have the required role.
     */
    public function authorizeRole(string $role): void
    {
        abort_unless($this->hasRole($role), 403, 'Unauthorized.');
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
