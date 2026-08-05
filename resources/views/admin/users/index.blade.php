@extends('layouts.admin')

@section('title', __('List of Users'))

@section('content')
    <div class="col-md-12" x-data="ListPage()" data-item-id="@json(session('item_id'))">
        <div class="row mb-3">
            <div class="col-md-6">
                <a class="btn btn-outline-dark" href="{{ route('admin.users.create') }}">
                    <span class="fas fa-user-plus"></span>
                    {{ __('Add User') }}
                </a>
            </div>

            <div class="col-md-6">
                <x-listpage-count :text="__('app.user_count')" :value="$users->total()" />
            </div>
        </div>

        <div class="row mb-3">
            <x-label for="status" class="col-md-auto">{{ __('Status') }}</x-label>
            <div class="col-md-3">
                <x-select name="status" :options="['all' => __('All')] + \App\Enums\UserStatus::labels()" x-model="params.status" x-on:change="load()" :selected="request()->query('status')" />
            </div>
        </div>

        <x-select class="mb-2" name="search_column" :options="
            [
                'name' => __('Name'),
                'email' => __('Email'),
            ]
        " x-model="params.search_column" :selected="request()->query('search_column')" />

        <x-listpage-searchbox class="mb-3" name="search_value" />

        <div>{{ $users->links() }}</div>

        <x-listpage-table>
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%"><x-listpage-sorter column="id" label="#" /></th>
                        <th style="width: 30%"><x-listpage-sorter column="name" :label="__('Name')" /></th>
                        <th style="width: 30%"><x-listpage-sorter column="email" :label="__('Email')" /></th>
                        <th class="text-center" style="width: 20%"><x-listpage-sorter column="last_online_at" :label="__('Active On')" /></th>
                        <th class="text-center" style="width: 10%">{{ __('Status') }}</th>
                        <th class="text-center" style="width: 5%">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(user, index) in data.data" x-bind:key="index">
                        <tr x-bind:id="'item-' + user.id">
                            <td class="text-center" x-text="data.meta.from + index"></td>
                            <td x-text="user.name"></td>
                            <td x-text="user.email"></td>
                            <td class="text-center" x-html="Helper.formatDate(user.last_online_at ?? '', 'Y-m-d H:i:s')"></td>
                            <td class="text-center">
                                <span class="badge px-2 py-1 text-uppercase" x-bind:class="'bg-' + UserStatus.contexts[user.status]" x-text="UserStatus.labels[user.status]"></span>
                            </td>
                            <td class="text-center">
                                <div class="mx-auto" style="width: 128px">
                                    <a class="btn btn-outline-dark btn-xs" x-bind:href="zroute('admin.users.show', user.id)"><span class="far fa-eye fa-fw"></span></a>
                                    <a class="btn btn-outline-dark btn-xs" x-bind:href="zroute('admin.users.edit', user.id)"><span class="far fa-edit fa-fw"></span></a>
                                    <button class="btn btn-outline-primary btn-xs" x-show="user.status === UserStatus.Inactive" x-on:click="activate(user)"><span class="fas fa-check fa-fw"></span></button>
                                    <button class="btn btn-outline-warning btn-xs" x-show="user.status === UserStatus.Active" x-on:click="ban(user)"><span class="fas fa-ban fa-fw"></span></button>
                                    <button class="btn btn-outline-primary btn-xs" x-show="user.status === UserStatus.Banned" x-on:click="unban(user)"><span class="fas fa-redo fa-fw"></span></button>
                                    <button class="btn btn-outline-danger btn-xs" x-on:click="remove(user)"><span class="far fa-trash-alt fa-fw"></span></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </x-listpage-table>

        <div>{{ $users->links() }}</div>
    </div>
@endsection

@push('js')
    @enum(\App\Enums\UserStatus::class)

    <script type="text/javascript">
        Object.assign(window.PageData, {
            users: @json($users),
        });
    </script>
    <script type="text/javascript" src="{{ asset('views/admin/users/index.js') }}"></script>
@endpush
