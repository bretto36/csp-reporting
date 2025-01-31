<?php

return [
    'enabled' => env('CSP_REPORTING_ENABLED', false),

    'uri' => env('CSP_REPORTING_URI', '/csp-report'),

    'route' => [
        'prefix'     => 'csp-reporting',
        'middleware' => ['web'],
    ],
];
