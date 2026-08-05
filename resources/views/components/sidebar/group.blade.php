@props(['icon', 'label', 'active' => null])

@php
    $open = $active && request()->routeIs($active);
    $id = 'app-sidebar-submenu-' . base_convert(mt_rand(16 ** 5, 16 ** 6 - 1), 10, 16);
@endphp

<li @class(['app-sidebar-group', 'is-open' => $open])>
    <button type="button" @class(['app-sidebar-link', 'active' => $open]) aria-controls="{{ $id }}" aria-expanded="{{ $open ? 'true' : 'false' }}">
        <span class="fas {{ $icon }} fa-fw app-sidebar-icon" aria-hidden="true"></span>
        <span class="app-sidebar-link-text">{{ $label }}</span>
        <span class="fas fa-chevron-right app-sidebar-arrow" aria-hidden="true"></span>
    </button>
    <ul class="app-sidebar-submenu" id="{{ $id }}">
        {{ $slot }}
    </ul>
</li>
