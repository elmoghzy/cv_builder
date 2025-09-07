<?php

return [
    // Comma-separated list of admin emails, e.g. "admin@cv.com, owner@mail.com"
    'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', '')))),

    // In local environment, allow any authenticated user to access the admin panel
    // Set to false in .env to disable: ADMIN_ALLOW_ANY_AUTH=false
    'allow_any_auth_in_local' => env('ADMIN_ALLOW_ANY_AUTH', true),
];
