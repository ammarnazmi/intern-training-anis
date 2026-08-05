@props(['icon', 'route' => null, 'href' => null, 'active' => null])

@php($active ??= $route)

<li>
    <a href="{{ $href ?? ($route ? route($route) : '#') }}" {{ $attributes->class(['app-sidebar-link', 'active' => $active && request()->routeIs($active)]) }}>
        <span class="fas {{ $icon }} fa-fw app-sidebar-icon" aria-hidden="true"></span>
        <span class="app-sidebar-link-text">{{ $slot }}</span>
    </a>
</li>
