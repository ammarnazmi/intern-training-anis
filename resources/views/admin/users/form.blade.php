@extends('layouts.admin')

@section('title', match (true) {
    request()->is('*/create') => __('Add User'),
    request()->is('*/edit') => __('Update User'),
})

@section('content')
    <div class="col-md-12">
        <x-form x-data="Form()" :bind-data="$user">
            <div class="row mb-3">
                <x-label for="name" class="col-md-2">{{ __('Name') }}</x-label>

                <div class="col-md-6">
                    <x-input type="text" name="name" placeholder="{{ __('Enter user\'s name...') }}" autofill />

                    <x-error for="name" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="email" class="col-md-2">{{ __('Email') }}</x-label>

                <div class="col-md-6">
                    <x-input type="email" name="email" placeholder="{{ __('Enter user\'s email...') }}" autofill />

                    <x-error for="email" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="locale" class="col-md-2">{{ __('Language') }}</x-label>

                <div class="col-md-6 pt-2">
                    <x-radio inline name="locale" :options="common_data('locales')" autofill />

                    <x-error for="locale" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="password" class="col-md-2">{{ __('Password') }}</x-label>

                <div class="col-md-6">
                    <x-password-input name="password" placeholder="{{ __('Enter user\'s password...') }}" autocomplete="new-password" />

                    @if (request()->is('*/edit'))
                        <x-help>{{ __('Leave a blank if preserving the original password.') }}</x-help>
                    @endif

                    <x-error for="password" />
                </div>
            </div>

            <hr />

            <div class="row mb-3">
                <div class="col-md-6 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ request()->is('*/create') ? __('Save') : __('Update') }}
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
                formRequest: {!! \App\Http\Requests\UserRequest::extractJson() !!},
            })
        );
    </script>
@endpush
