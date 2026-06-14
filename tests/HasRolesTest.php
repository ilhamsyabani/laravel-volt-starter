<?php

namespace Ilhamsyabani\VoltStarter\Tests;

use Ilhamsyabani\VoltStarter\Traits\HasRoles;

it('has roles trait works correctly', function () {
    $user = new class {
        use HasRoles;
        public string $role = 'user';
    };

    expect($user->isUser())->toBeTrue();
    expect($user->isAdmin())->toBeFalse();
    expect($user->isSuperAdmin())->toBeFalse();
    expect($user->hasRole('user'))->toBeTrue();
    expect($user->hasRole('admin'))->toBeFalse();
});

it('admin can access user and admin level', function () {
    $admin = new class {
        use HasRoles;
        public string $role = 'admin';
    };

    expect($admin->isAdmin())->toBeTrue();
    expect($admin->isUser())->toBeFalse();
    expect($admin->isSuperAdmin())->toBeFalse();
    expect($admin->hasRole('user'))->toBeTrue();
    expect($admin->hasRole('admin'))->toBeTrue();
    expect($admin->hasRole('superadmin'))->toBeFalse();
});

it('superadmin can access all levels', function () {
    $superadmin = new class {
        use HasRoles;
        public string $role = 'superadmin';
    };

    expect($superadmin->isSuperAdmin())->toBeTrue();
    expect($superadmin->isAdmin())->toBeTrue();
    expect($superadmin->hasRole('user'))->toBeTrue();
    expect($superadmin->hasRole('admin'))->toBeTrue();
    expect($superadmin->hasRole('superadmin'))->toBeTrue();
});

it('hasRole accepts array of roles', function () {
    $user = new class {
        use HasRoles;
        public string $role = 'user';
    };

    expect($user->hasRole(['user', 'admin']))->toBeTrue();
    expect($user->hasRole(['admin', 'superadmin']))->toBeFalse();
});

it('hasAnyRole returns true if user has any of the roles', function () {
    $user = new class {
        use HasRoles;
        public string $role = 'user';
    };

    expect($user->hasAnyRole(['admin', 'superadmin']))->toBeFalse();
    expect($user->hasAnyRole(['user', 'admin']))->toBeTrue();
});

it('hasAllRoles returns true only if user has all roles', function () {
    $admin = new class {
        use HasRoles;
        public string $role = 'admin';
    };

    expect($admin->hasAllRoles(['admin']))->toBeTrue();
    expect($admin->hasAllRoles(['admin', 'user']))->toBeFalse();
});

it('getRoleBadgeColor returns correct colors', function () {
    $superadmin = new class {
        use HasRoles;
        public string $role = 'superadmin';
    };

    $admin = new class {
        use HasRoles;
        public string $role = 'admin';
    };

    $user = new class {
        use HasRoles;
        public string $role = 'user';
    };

    expect($superadmin->getRoleBadgeColor())->toBe('red');
    expect($admin->getRoleBadgeColor())->toBe('amber');
    expect($user->getRoleBadgeColor())->toBe('zinc');
});

it('getRoleDisplayName returns correct names', function () {
    $superadmin = new class {
        use HasRoles;
        public string $role = 'superadmin';
    };

    $admin = new class {
        use HasRoles;
        public string $role = 'admin';
    };

    $user = new class {
        use HasRoles;
        public string $role = 'user';
    };

    expect($superadmin->getRoleDisplayName())->toBe('Super Admin');
    expect($admin->getRoleDisplayName())->toBe('Admin');
    expect($user->getRoleDisplayName())->toBe('User');
});

it('static roles method returns all available roles', function () {
    $roles = resolve(HasRoles::class)::roles();

    expect($roles)->toBe(['superadmin', 'admin', 'user']);
});
