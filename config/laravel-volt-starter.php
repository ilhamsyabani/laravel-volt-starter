<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    | CSS class applied to <html> tag for color theming.
    | Available: '' (default/indigo), 'theme-rose', 'theme-emerald',
    |            'theme-amber', 'theme-sky', or your own custom theme class.
    */
    'theme' => env('VOLT_STARTER_THEME', ''),

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    | Available roles for the HasRoles trait.
    | The order here defines the hierarchy (lowest to highest).
    */
    'roles' => ['user', 'admin', 'superadmin'],

    /*
    |--------------------------------------------------------------------------
    | Default Role
    |--------------------------------------------------------------------------
    | The default role assigned to new users.
    */
    'default_role' => env('VOLT_STARTER_DEFAULT_ROLE', 'user'),

];
