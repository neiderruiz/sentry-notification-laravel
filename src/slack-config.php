<?php

return [
    'sentry_token' => env('SENTRY_TOKEN', false),
    'slack' => [
        'webhook_endpoint' => env('SLACK_WEBHOOK_ENDPOINT', false),
    ],
    'sentry' => [
        'url_api' => env('SENTRY_URL_API', false),
        'organization' => env('SENTRY_ORGANIZATION', false),
        'project' => env('SENTRY_PROJECT', false),
    ],
];
