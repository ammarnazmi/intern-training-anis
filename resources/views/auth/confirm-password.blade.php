@extends('layouts.' . $guard)

@section('title', __('Confirm Your Password'))

@section('content')
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <x-alert type="warning" class="mb-4">
                    <span class="fas fa-circle-exclamation"></span>
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </x-alert>

                <x-form x-data="Form()" is-post :action="route($guard . '.password.confirm')">
                    <div class="row mb-3">
                        <x-label for="password" class="col-md-2">{{ __('Password') }}</x-label>
                        <div class="col-md-5">
                            <x-password-input name="password" placeholder="{{ __('Enter your password...') }}" autocomplete="current-password" autofocus />
                            <x-error for="password" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="d-grid col-md-5 offset-md-2">
                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Confirm') }}
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
                rules: {
                    password: ['required', 'min:8'],
                },
            })
        );
    </script>
@endpush
