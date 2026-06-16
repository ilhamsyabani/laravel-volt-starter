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
    public function hasRole(string $role): bool
    {
        $hierarchy = ['user' => 1, 'admin' => 2, 'superadmin' => 3];
        $userLevel = $hierarchy[$this->role] ?? 0;
        $requiredLevel = $hierarchy[$role] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Abort with 403 if user doesn't have the required role.
     */
    public function authorizeRole(string $role): void
    {
        abort_unless($this->hasRole($role), 403, 'Unauthorized.');
    }
}
