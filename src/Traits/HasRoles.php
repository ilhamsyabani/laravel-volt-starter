<?php

namespace Ilhamsyabani\VoltStarter\Traits;

trait HasRoles
{
    /**
     * Check if user is a superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is an admin or superadmin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user is a regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has at least a given role level.
     * Hierarchy: superadmin > admin > user
     */
    public function hasRole(string|array $role): bool
    {
        if (is_array($role)) {
            return $this->hasAnyRole($role);
        }

        $hierarchy = ['user' => 1, 'admin' => 2, 'superadmin' => 3];
        $userLevel = $hierarchy[$this->role] ?? 0;
        $requiredLevel = $hierarchy[$role] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get badge color for the user's role.
     */
    public function getRoleBadgeColor(): string
    {
        return match ($this->role) {
            'superadmin' => 'red',
            'admin' => 'amber',
            default => 'zinc',
        };
    }

    /**
     * Get display name for the user's role.
     */
    public function getRoleDisplayName(): string
    {
        return match ($this->role) {
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            default => 'User',
        };
    }

    /**
     * Get all available roles.
     */
    public static function roles(): array
    {
        return ['superadmin', 'admin', 'user'];
    }

    /**
     * Abort with 403 if user doesn't have the required role.
     */
    public function authorizeRole(string $role): void
    {
        abort_unless($this->hasRole($role), 403, 'Unauthorized.');
    }
}
