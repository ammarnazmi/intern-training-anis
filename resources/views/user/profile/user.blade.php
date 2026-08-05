@extends('layouts.user')

@section('title', __('Account Profile'))

@section('content')
    <div class="col-md-12 mx-auto">
        <x-form x-data="Form()" is-put :action="route('user.profile.user.update')" :bind-data="$user">
            <div class="row mb-3">
                <x-label for="name" class="col-md-2">{{ __('Name') }}</x-label>

                <div class="col-md-5">
                    <x-input type="text" name="name" autofill />

                    <x-error for="name" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="email" class="col-md-2">{{ __('Email') }}</x-label>

                <div class="col-md-5">
                    <x-input type="email" name="email" autofill />

                    <x-error for="email" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="locale" class="col-md-2">{{ __('Language') }}</x-label>

                <div class="col-md-5 pt-2">
                    <x-radio inline name="locale" :options="common_data('locales')" autofill />

                    <x-error for="locale" />
                </div>
            </div>

            <hr />

            <div class="row mb-3">
                <div class="col-md-5 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update') }}
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
                formRequest: {!!
                    \App\Http\Requests\UserRequest::extractJson([
                        'method' => 'PUT',
                        'id' => $user->id,
                    ])
                !!},
            })
        );
    </script>
@endpush
