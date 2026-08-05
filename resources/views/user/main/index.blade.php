@extends('layouts.user')

@section('title', __('Main Page'))

@section('content')
    <div class="col-md-12 mx-auto">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>{{ __('Welcome :name!', ['name' => request()->user()->name]) }}</p>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset('views/user/main/index.js') }}"></script>
@endpush
