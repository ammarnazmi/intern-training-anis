@php
    LinkHeader::prepend([
        config('services.cloudflare.fonts.enabled') ? '' : 'https://fonts.gstatic.com/' => [
            'rel' => 'preconnect',
            'crossorigin' => '',
        ],
        'https://cdn.jsdelivr.net/' => [
            'rel' => 'preconnect',
            'crossorigin' => '',
        ],
        asset('css/app.css') => [
            'rel' => 'preload',
            'as' => 'style',
        ],
        config('services.cloudflare.fonts.enabled') ? '' : 'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap' => [
            'rel' => 'preload',
            'as' => 'style',
        ],
        'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.3.1/css/all.min.css' => [
            'rel' => 'preload',
            'as' => 'style',
            'integrity' => 'sha256-4Lad8m4ZWW1Lgb9+sMVLYEfnIh7BjV1NQMEe79Pviks=',
            'crossorigin' => 'anonymous',
        ],
        asset('js/app.js') => [
            'rel' => 'preload',
            'as' => 'script',
        ],
        config('custom.jslang_embed_enabled') ? '' : app(\Apih\JsLang\JsLang::class)->getUrl($_locale, 'all') => [
            'rel' => 'preload',
            'as' => 'script',
        ],
    ]);
@endphp
