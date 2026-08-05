@extends('layouts.regular')

@section('title', __('Forgot Password'))

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

                <div class="text-sm mb-4">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                @if (session('status'))
                    <x-alert type="success">
                        {{ session('status') }}
                    </x-alert>
                @endif

                <x-form x-data="Form()" is-post :action="route($guard . '.password.email')">
                    <div class="row mb-3">
                        <x-label for="email" class="col-md-2">{{ __('Email') }}</x-label>
                        <div class="col-md-10">
                            <x-input type="text" name="email" placeholder="{{ __('Enter your email...') }}" value="{{ old('email') }}" />
                            <x-error for="email" />
                        </div>
                    </div>

                    @if (config('services.hcaptcha.sitekey'))
                        <div class="row mb-3">
                            <div class="col-md-10 offset-md-2">
                                <x-hcaptcha name="captcha" />
                                <x-error for="captcha" />
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="d-grid col-md-10 offset-md-2">
                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Email Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </x-form>
            </div>

            <div class="card-footer text-center py-3">
                <a class="d-block d-sm-inline text-decoration-none" href="{{ route($guard . '.login') }}">
                    <span class="fas fa-angle-left"></span>
                    {{ __('Back to Login') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Alpine.data('Form', () =>
            window.AlpineComponents.Form({
                formRequest: {!! \App\Http\Requests\Auth\ForgotPasswordRequest::extractJson() !!},
            })
        );
    </script>
@endpush
