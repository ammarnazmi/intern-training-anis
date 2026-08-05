@props(['icon', 'label', 'route' => null, 'href' => null, 'active' => null, 'badge' => null])

@php($active ??= $route)

<a
    href="{{ $href ?? ($route ? route($route) : '#') }}"
    {{ $attributes->class(['app-topbar-action', 'active' => $active && request()->routeIs($active)]) }}
    aria-label="{{ $label }}"
    title="{{ $label }}"
>
    <span class="fas {{ $icon }} fa-fw" aria-hidden="true"></span>
    @if (filled($badge))
        <span class="app-topbar-action-badge">{{ $badge }}</span>
    @endif

    {{ $slot }}
</a>
