<?php

return [
    'bolt_enabled' => env('CUSTOM_BOLT_ENABLED', true),

    'jslang_embed_enabled' => env('CUSTOM_JSLANG_EMBED_ENABLED', false),

    'service_worker' => [
        'enabled' => env('CUSTOM_SERVICE_WORKER_ENABLED', false),

        'unregister' => env('CUSTOM_SERVICE_WORKER_UNREGISTER', false),
    ],
];
