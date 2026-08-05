@props(['route'])

{{-- The picked locale is written to the user's profile, so it carries over to the next session --}}
<x-topbar.dropdown class="app-topbar-action" icon="fa-language" :caret="false" :aria-label="__('Language')" :title="__('Language')">
    @foreach (common_data('locales') as $code => $label)
        <li>
            <x-button-form as-link method="put" :action="route($route)" :data="['locale' => $code]" class="dropdown-item app-topbar-locale {{ app()->getLocale() === $code ? 'active' : '' }}" bolt-preserve-scroll>
                <img src="{{ asset('images/flags/' . $code . '.svg') }}" alt="" class="app-topbar-locale-flag" />
                {{ $label }}
            </x-button-form>
        </li>
    @endforeach
</x-topbar.dropdown>
