<?php

return [
    'slack' => [
        'webhook_endpoint' => env('SLACK_WEBHOOK_ENDPOINT', false),
        'send_message' => env('SLACK_SEND_MESSAGE', false),
    ],
    'sentry' => [
        'url_api' => env('SENTRY_URL_API', false),
        'organization' => env('SENTRY_ORGANIZATION', false),
        'project' => env('SENTRY_PROJECT', false),
        'token' => env('SENTRY_TOKEN', false),
    ],
];
