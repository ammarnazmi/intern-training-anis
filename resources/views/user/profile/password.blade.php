@extends('layouts.user')

@section('title', __('Change Password'))

@section('content')
    <div class="col-md-12 mx-auto">
        <x-form x-data="Form()" is-put :action="route('user.profile.password.update')">
            <div class="row mb-3">
                <x-label for="current_password" class="col-md-2">{{ __('Current Password') }}</x-label>

                <div class="col-md-5">
                    <x-password-input name="current_password" placeholder="{{ __('Enter current password...') }}" />

                    <x-error for="current_password" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="password" class="col-md-2">{{ __('New Password') }}</x-label>

                <div class="col-md-5">
                    <x-password-input name="password" placeholder="{{ __('Enter new password...') }}" show-strength-meter />

                    <x-help>{{ __('Minimum 8 characters.') }}</x-help>

                    <x-error for="password" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="password_confirmation" class="col-md-2">{{ __('New Password Confirmation') }}</x-label>

                <div class="col-md-5">
                    <x-password-input name="password_confirmation" placeholder="{{ __('Confirm new password...') }}" />

                    <x-error for="password_confirmation" />
                </div>
            </div>

            <hr />

            <div class="row mb-3">
                <div class="col-md-5 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Change Password') }}
                    </button>
                </div>
            </div>
        </x-form>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Alpine.data('Form', () =>
            window.AlpineComponents.Form({
                formRequest: {!! \App\Http\Requests\UpdatePasswordRequest::extractJson() !!},
            })
        );
    </script>
@endpush
