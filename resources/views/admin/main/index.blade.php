@extends('layouts.admin')

@section('title', __('Main Page'))

@section('content')
    <div class="col-md-12 mx-auto">
        <div class="row" x-data="CountsDisplay()">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="fas fa-users fa-fw fa-3x text-success"></span>
                        <span class="fas fa-spinner fa-spin text-muted h4 ms-4" x-show="!loaded"></span>
                        <span class="text-center h1 ps-4" x-text="data.users_count.toLocaleString()" x-show="loaded" x-cloak></span>
                        <span class="text-dark ps-2 lh-lg">{{ __('Users') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-4" x-data="OnlineUsers()">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4>{{ __('Online Users') }}</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 80%">{{ __('Name') }}</th>
                                    <th class="text-end" style="width: 15%">{{ __('Seconds Ago') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(user, index) in users" x-bind:key="index">
                                    <tr>
                                        <td x-text="index + 1"></td>
                                        <td x-text="user.name"></td>
                                        <td class="text-end" x-text="user.ago"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset('views/admin/main/index.js') }}"></script>
@endpush
