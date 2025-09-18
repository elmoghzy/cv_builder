<?php

return [
    // Comma-separated list of admin emails, e.g. "admin@cv.com, owner@mail.com"
    'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', '')))),
];
