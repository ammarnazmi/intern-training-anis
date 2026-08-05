@php
    $_locale = app()->getLocale();
@endphp

@include('layouts.base_link_header')

@section('footer')
    <footer class="footer">
        <div>
            {!!
                __('app.footer', [
                    'organization' => '<a class="fw-semibold text-muted text-decoration-none" href="https://onpay.my" target="_blank">Onpay Solutions Sdn Bhd</a>',
                    'year' => copyright_year(2024),
                ])
            !!}
        </div>
    </footer>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $_locale) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="bolt-enabled" content="{{ json_encode(config('custom.bolt_enabled')) }}" />
        <title>@yield('title'){!! view()->hasSection('title') ? ' &ndash; ' : '' !!}{{ config('app.name') }}</title>

        @if (view()->hasSection('description'))
            <meta name="description" content="@yield('description')" />
        @endif

        <!-- DNS prefetch -->
        @if (!config('services.cloudflare.fonts.enabled'))
            <link rel="dns-prefetch" href="//fonts.gstatic.com" />
        @endif

        <link rel="dns-prefetch" href="//cdn.jsdelivr.net" />

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

        <!-- Fonts -->
        @if (!request()->isBolt())
            <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" bolt-pin />
        @endif

        @yield('head-extra')

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" bolt-pin />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.3.1/css/all.min.css" integrity="sha256-4Lad8m4ZWW1Lgb9+sMVLYEfnIh7BjV1NQMEe79Pviks=" crossorigin="anonymous" bolt-pin />
        @stack('css')

        <!-- JavaScript noop -->
        <script bolt-pin>
            const noop = () => {};
        </script>
    </head>

    <body>
        <div id="app-container">
            @yield('body')
            @yield('footer')
        </div>

        <!-- Templates -->
        @stack('templates')

        <!-- JavaScript -->
        <script type="text/javascript" src="{{ asset('js/app.js') }}" bolt-pin></script>
        @versionedAssets

        @if (config('custom.jslang_embed_enabled'))
            <script type="text/javascript" bolt-pin>
                {!! app(\Apih\JsLang\JsLang::class)->getContents($_locale, 'all', true) !!};
            </script>
        @else
            <script type="text/javascript" src="{{ app(\Apih\JsLang\JsLang::class)->getUrl($_locale, 'all') }}" bolt-pin></script>
        @endif

        <script type="text/javascript" bolt-pin>
            App.configure({
                url: @json(url('/')),
                topOffset: 30,
                serviceWorker: {
                    url: @json(asset('service-worker.js')),
                    enabled: @json(config('custom.service_worker.enabled')),
                    unregister: @json(config('custom.service_worker.unregister')),
                },
                routes: @json(app()->isProduction() ? [] : zroute_source(), JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES),
            });
        </script>

        <script type="text/javascript" bolt-pin="app_dynamic">
            App.configure({
                authed: @json(auth()->check()),
                locale: @json($_locale),
                notification: @json(session('notification')),
                errors: @json(isset($errors) ? flatten_error_messages($errors) : [], JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES),
            });
        </script>

        @stack('js')
    </body>
</html>
