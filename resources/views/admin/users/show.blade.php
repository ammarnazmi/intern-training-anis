@extends('layouts.admin')

@section('title', __('View User'))

@section('content')
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th style="width: 180px">{{ __('Name') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Email Verified At') }}</th>
                        <td>{{ $user->email_verified_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Language') }}</th>
                        <td>{{ common_data('locales')[$user->locale] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Status') }}</th>
                        <td>
                            <span class="badge bg-{{ $user->status->context() }} px-2 py-1 text-uppercase">
                                {{ $user->status->label() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Last Login At') }}</th>
                        <td>{{ $user->last_login_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Last Online At') }}</th>
                        <td>{{ $user->last_online_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Banned At') }}</th>
                        <td>{{ $user->banned_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Created At') }}</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Updated At') }}</th>
                        <td>{{ $user->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
