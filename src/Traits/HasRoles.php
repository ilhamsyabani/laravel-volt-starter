<?php

namespace Ilhamsyabani\VoltStarter\Traits;

/**
 * HasRoles Trait for role-based authorization.
 *
 * NOTE: For new projects, prefer extending VoltUser abstract class instead.
 * This trait is an alternative for projects that already have their own User model.
 *
 * Usage:
 *   class User extends Authenticatable
 *   {
 *       use HasRoles;
 *
 *       protected string $roleColumn = 'role'; // default
 *   }
 *
 * Role Hierarchy (lowest to highest):
 *   user < admin < superadmin
 */
trait HasRoles
{
    /**
     * The role column name (can be overridden in the model).
     */
    protected string $roleColumn = 'role';

    /**
     * Available roles in order of hierarchy.
     */
    public function roles(): array
    {
        return array_reverse(config('laravel-volt-starter.roles', ['superadmin', 'admin', 'user']));
    }

    /**
     * Check if user is a superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->{$this->roleColumn} === 'superadmin';
    }

    /**
     * Check if user is an admin or superadmin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->{$this->roleColumn}, ['admin', 'superadmin']);
    }

    /**
     * Check if user is a regular user.
     */
    public function isUser(): bool
    {
        return $this->{$this->roleColumn} === 'user';
    }

    /**
     * Check if user has at least a given role level.
     * Supports hierarchy-based checking (user can access lower roles).
     *
     * @param string|array $roles Single role or array of roles
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        $allRoles = $this->roles();
        $userRoleIndex = array_search($this->{$this->roleColumn}, $allRoles);

        if ($userRoleIndex === false) {
            return in_array($this->{$this->roleColumn}, $roles);
        }

        foreach ($roles as $role) {
            $requiredRoleIndex = array_search($role, $allRoles);

            if ($requiredRoleIndex !== false && $userRoleIndex >= $requiredRoleIndex) {
                return true;
            }

            if ($this->{$this->roleColumn} === $role) {
                return true;
            }
        }

        return false;
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
     */
    public function hasAllRoles(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (! $this->hasRole($role)) {
                return false;
            }
        }

        return true;
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
}
