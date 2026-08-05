@extends('layouts.regular')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <img class="d-block mx-auto mt-3" src="{{ asset('images/logo.png') }}" style="width: 80px" />
                <h1 class="text-center mt-2">Welcome to LAB Stack Boilerplate</h1>
                <div class="text-center mt-3">
                    {{-- format-ignore-start --}}
                    <b>LAB</b> is an acronym, based on the first character of <b>L</b>aravel, <b>A</b>lpine.js and <b>B</b>ootstrap.
                    {{-- format-ignore-end --}}
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="text-center">
                @if (Route::has('admin.login'))
                    <a href="{{ route('admin.login') }}">Admin Login</a>
                @endif

                <span class="mx-2">|</span>

                @if (Route::has('user.login'))
                    <a href="{{ route('user.login') }}">User Login</a>
                @endif
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-4 mb-2">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">{{-- format-ignore-start --}}<b>L</b>aravel 13{{-- format-ignore-end --}}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Laravel is an open-source PHP web application framework with a modern, expressive syntax.</p>
                        <a href="https://laravel.com" target="_blank">Learn More &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-2">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">{{-- format-ignore-start --}}<b>A</b>lpine.js 3{{-- format-ignore-end --}}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Alpine.js is a progressive JavaScript framework for building fast interactive web applications.</p>
                        <a href="https://alpinejs.dev" target="_blank">Learn More &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-2">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">{{-- format-ignore-start --}}<b>B</b>ootstrap 5{{-- format-ignore-end --}}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.</p>
                        <a href="https://getbootstrap.com" target="_blank">Learn More &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
