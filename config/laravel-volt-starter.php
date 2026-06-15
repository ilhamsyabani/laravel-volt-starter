<?php
return [
    'theme'        => env('VOLT_STARTER_THEME', ''),
    'roles'        => ['user', 'admin', 'superadmin'],
    'default_role' => env('VOLT_STARTER_DEFAULT_ROLE', 'user'),
];