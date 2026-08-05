@extends('layouts.regular')

@section('content')
    <div class="col-md-6 mx-auto" style="margin-top: 5rem">
        <div class="text-center">
            <h1 class="text-danger" style="font-size: 6rem">@yield('code')</h1>
            <p style="font-size: 1.8rem">@yield('message')</p>
        </div>
    </div>
@endsection
