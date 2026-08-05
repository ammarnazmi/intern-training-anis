@extends('layouts.regular')

@section('title', __('Reset Password'))

@section('content')
    <div class="col-md-7 mx-auto my-5">
        <div class="card">
            @if ($guard == 'admin')
                <div class="badge bg-danger card-badge top-0 end-0">ADMIN</div>
            @endif

            <div class="card-header">
                <img class="d-block mx-auto" src="{{ asset('images/logo.png') }}" style="width: 100px" />
            </div>

            <div class="card-body">
                <h2 class="text-center mb-3">@yield('title')</h2>

                <x-form x-data="Form()" is-post :action="route($guard . '.password.update')">
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}" />

                    <div class="row mb-3">
                        <x-label for="email" class="col-md-3">{{ __('Email') }}</x-label>
                        <div class="col-md-9">
                            <x-input type="text" name="email" placeholder="{{ __('Enter your email...') }}" value="{{ $request->query('email') }}" />

                            <x-error for="email" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <x-label for="password" class="col-md-3">{{ __('New Password') }}</x-label>

                        <div class="col-md-9">
                            <x-password-input name="password" placeholder="{{ __('Enter new password...') }}" show-strength-meter />

                            <x-help>{{ __('Minimum 8 characters.') }}</x-help>

                            <x-error for="password" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <x-label for="password_confirmation" class="col-md-3">{{ __('New Password Confirmation') }}</x-label>

                        <div class="col-md-9">
                            <x-password-input name="password_confirmation" placeholder="{{ __('Confirm new password...') }}" />

                            <x-error for="password_confirmation" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="d-grid col-md-9 offset-md-3">
                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Alpine.data('Form', () =>
            window.AlpineComponents.Form({
                formRequest: {!! \App\Http\Requests\Auth\ResetPasswordRequest::extractJson() !!},
            })
        );
    </script>
@endpush
