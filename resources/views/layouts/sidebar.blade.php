@extends('layouts.base')

@section('footer', '')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('views/layouts/sidebar.scss') }}" bolt-pin />
@endpush

@section('body')
    {{-- `is-collapsed` comes from the cookie the script writes, so the sidebar never flashes open before Alpine boots --}}
    <div x-data="SidebarLayout('{{ $area }}')" @class(['app-wrapper', 'is-collapsed' => request()->cookie('sidebar_collapsed')]) x-bind:class="{ 'is-collapsed': collapsed, 'is-mobile-open': mobileOpen }" x-on:keydown.escape.window="closeMobile()">
        {{-- Mobile overlay backdrop --}}
        <div class="app-backdrop" x-on:click="closeMobile()"></div>

        {{-- Sidebar --}}
        <aside id="app-sidebar" class="app-sidebar">
            {{-- Sidebar header --}}
            <div class="app-sidebar-header">
                <a href="{{ route($area . '.main') }}" class="app-sidebar-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="logo" class="app-sidebar-logo" />
                    <span class="app-sidebar-brand-text">
                        {{ config('app.name') }}
                    </span>
                </a>
                <button type="button" class="app-sidebar-close" aria-label="{{ __('Close menu') }}" x-on:click="closeMobile()">
                    <span class="fas fa-xmark fa-fw" aria-hidden="true"></span>
                </button>
                {{-- Optional. Apps that run an admin area alongside a user one badge them to tell the two apart; a single-area app just leaves it out --}}
                @if (filled($badge ?? null))
                    <span class="badge {{ $badgeClass ?? 'bg-secondary' }} app-sidebar-badge">{{ $badge }}</span>
                @endif
            </div>

            {{-- Navigation. Following a link dismisses the drawer, so it slides out instead of vanishing with the page swap --}}
            <nav class="app-sidebar-nav" aria-label="{{ __('Main navigation') }}" x-on:click="$event.target.closest('a') && closeMobile()">
                <ul class="app-sidebar-links">
                    @yield('sidebar-menu')
                </ul>
            </nav>
        </aside>

        {{-- Main content --}}
        <div class="app-main">
            {{-- Top bar --}}
            <header class="app-topbar">
                <div class="app-topbar-start">
                    <button type="button" class="app-topbar-toggle" aria-label="{{ __('Toggle menu') }}" aria-controls="app-sidebar" x-on:click="toggle()">
                        <span class="fas fa-fw app-topbar-toggle-mobile" aria-hidden="true" x-bind:class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></span>
                        <span class="fas fa-fw app-topbar-toggle-desktop" aria-hidden="true" x-bind:class="collapsed ? 'fa-angles-right' : 'fa-angles-left'"></span>
                    </button>

                    @yield('topbar-start')
                </div>
                <div class="app-topbar-end">
                    @yield('topbar-end')
                </div>
            </header>

            {{-- Page content --}}
            <main class="app-content">
                <h1 class="page-header">@yield('title')</h1>
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="app-footer">
                {!!
                    __('app.footer', [
                        'organization' => '<a class="fw-semibold text-muted text-decoration-none" href="https://onpay.my" target="_blank">Onpay Solutions Sdn Bhd</a>',
                        'year' => copyright_year(2024),
                    ])
                !!}
            </footer>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset('views/layouts/sidebar.js') }}" bolt-pin></script>
@endpush
