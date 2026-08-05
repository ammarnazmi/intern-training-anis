@extends('layouts.' . $guard)

@section('title', __('Verify Your Email Address'))

@section('content')
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                @if (session('status') == 'verification-link-sent')
                    <x-alert type="success">
                        <span class="fas fa-circle-check"></span>
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </x-alert>
                @endif

                @php
                    $requestLink = Blade::render('<x-button-form class="btn btn-link p-0 m-0 align-baseline" action="' . route($guard . '.verification.send') . '">' . __('click here to request another') . '</x-button-form>');
                @endphp

                {!!
                    __('A verification link has been sent to :email. If you did not receive the email, :request-link. In case you entered the email wrongly during registration process, please contact support for help.', [
                        'email' => '<b>' . e(request()->user()->email) . '</b>',
                        'request-link' => $requestLink,
                    ])
                !!}
            </div>
        </div>
    </div>
@endsection
