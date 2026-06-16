<?php

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
