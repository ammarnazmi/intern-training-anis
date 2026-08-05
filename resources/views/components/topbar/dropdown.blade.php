@props(['icon' => null, 'label' => null, 'caret' => true, 'align' => 'end', 'trigger' => null])

<div class="dropdown">
    {{--
        A custom `trigger` slot replaces the icon/label/caret arrangement entirely, for things
        like an avatar image that do not fit that shape
    --}}
    <a {{ $attributes->class('app-topbar-dropdown')->merge(['href' => '#']) }} role="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if (filled($trigger))
            {{ $trigger }}
        @else
            @if ($icon)
                <span class="fas {{ $icon }} fa-fw" aria-hidden="true"></span>
            @endif

            @if (filled($label))
                <span class="app-topbar-dropdown-label">{{ $label }}</span>
            @endif

            @if ($caret)
                <span class="fas fa-chevron-down fa-fw fa-xs app-topbar-dropdown-caret" aria-hidden="true"></span>
            @endif
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-{{ $align }}">
        {{ $slot }}
    </ul>
</div>
