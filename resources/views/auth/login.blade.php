@extends('layouts.regular')

@section('title', __('Login'))

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

                @if (session('status'))
                    <x-alert type="success">
                        {{ session('status') }}
                    </x-alert>
                @endif

                @if (session('banned'))
                    <x-alert type="danger">
                        {{ session('banned') }}
                    </x-alert>
                @endif

                <x-form x-data="Form()" is-post :action="route($guard . '.login')">
                    <div class="row mb-3">
                        <x-label for="email" class="col-md-2">{{ __('Email') }}</x-label>
                        <div class="col-md-10">
                            <x-input type="text" name="email" placeholder="{{ __('Enter your email...') }}" value="{{ session('email') }}" autofocus />
                            <x-error for="email" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <x-label for="password" class="col-md-2">{{ __('Password') }}</x-label>
                        <div class="col-md-10">
                            <x-password-input name="password" placeholder="{{ __('Enter your password...') }}" />
                            <x-error for="password" />
                        </div>
                    </div>

                    @if (config('services.cloudflare.turnstile.sitekey'))
                        <div class="row mb-3">
                            <div class="col-md-10 offset-md-2">
                                <x-turnstile name="captcha" />
                                <x-error for="captcha" />
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="d-grid col-md-10 offset-md-2">
                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </x-form>
            </div>

            <div class="card-footer text-center py-3">
                @if (Route::has($guard . '.register'))
                    <a class="d-block d-sm-inline text-decoration-none mb-1" href="{{ route($guard . '.register') }}">{{ __('Register') }}</a>
                @endif

                @if (Route::has($guard . '.register') && Route::has($guard . '.password.request'))
                    <span class="d-none d-sm-inline mx-2">|</span>
                @endif

                @if (Route::has($guard . '.password.request'))
                    <a class="d-block d-sm-inline text-decoration-none" href="{{ route($guard . '.password.request') }}">{{ __('Forgot Password') }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Alpine.data('Form', () =>
            window.AlpineComponents.Form({
                formRequest: {!! \App\Http\Requests\Auth\LoginRequest::extractJson() !!},
            })
        );
    </script>
@endpush
