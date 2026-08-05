@props(['route'])

<li>
    <x-button-form as-link class="dropdown-item text-danger" :action="route($route)">
        <span class="fas fa-right-from-bracket fa-fw me-2" aria-hidden="true"></span>
        {{ $slot->isEmpty() ? __('Log Out') : $slot }}
    </x-button-form>
</li>
