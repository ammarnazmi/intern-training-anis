@extends('layouts.sidebar', ['area' => 'admin', 'badge' => 'ADMIN', 'badgeClass' => 'bg-danger'])

{{-- Note: the `area` above is passed to the layout, not to this file, so routes here are spelled out in full --}}
@section('topbar-end')
    <x-topbar.locale route="admin.profile.locale" />

    <x-topbar.dropdown icon="fa-circle-user" :label="Auth::user()->name">
        <x-topbar.link route="admin.profile.user.show" icon="fa-user-gear">{{ __('Account Profile') }}</x-topbar.link>
        <x-topbar.link route="admin.profile.password.show" icon="fa-key">{{ __('Change Password') }}</x-topbar.link>
        <x-topbar.divider />
        <x-topbar.logout route="admin.logout" />
    </x-topbar.dropdown>
@endsection

@section('sidebar-menu')
    <x-sidebar.link route="admin.main" icon="fa-gauge-high">{{ __('Main') }}</x-sidebar.link>
    <x-sidebar.link route="admin.users.index" active="admin.users.*" icon="fa-users">{{ __('Users') }}</x-sidebar.link>
@endsection
