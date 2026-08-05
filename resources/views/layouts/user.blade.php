@extends('layouts.sidebar', ['area' => 'user'])

{{-- Apps that identify users by a username or email instead swap the label here --}}
@section('topbar-end')
    <x-topbar.locale route="user.profile.locale" />

    <x-topbar.dropdown icon="fa-circle-user" :label="Auth::user()->name">
        <x-topbar.link route="user.profile.user.show" icon="fa-user-gear">{{ __('Account Profile') }}</x-topbar.link>
        <x-topbar.link route="user.profile.password.show" icon="fa-key">{{ __('Change Password') }}</x-topbar.link>
        <x-topbar.divider />
        <x-topbar.logout route="user.logout" />
    </x-topbar.dropdown>
@endsection

@section('sidebar-menu')
    <x-sidebar.link route="user.main" icon="fa-gauge-high">{{ __('Main') }}</x-sidebar.link>
@endsection
