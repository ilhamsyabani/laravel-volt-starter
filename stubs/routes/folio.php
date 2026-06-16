<?php

use Laravel\Folio\Folio;

Folio::path(resource_path('views/pages'))->middleware([
    '*'            => [],
    'dashboard'    => ['auth', 'verified'],
    'settings/*'   => ['auth', 'verified'],
    'showcase'     => ['auth', 'verified'],
]);
