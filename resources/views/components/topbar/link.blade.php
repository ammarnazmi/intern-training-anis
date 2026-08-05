@props(['icon', 'route' => null, 'href' => null, 'active' => null])

@php($active ??= $route)

<li>
    <a href="{{ $href ?? ($route ? route($route) : '#') }}" {{ $attributes->class(['dropdown-item', 'active' => $active && request()->routeIs($active)]) }}>
        <span class="fas {{ $icon }} fa-fw me-2" aria-hidden="true"></span>
        {{ $slot }}
    </a>
</li>
