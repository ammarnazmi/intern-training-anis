@extends('layouts.admin')

@section('title', __('Subproducts for :product', ['product' => $product->name]))

@section('content')
    <div class="col-md-12" x-data="SubproductListPage()" data-item-id="@json(session('item_id'))">
        <div class="row mb-3">
            <div class="col-md-6">
                <a class="btn btn-outline-dark" href="{{ route('admin.products.subproducts.create', $product) }}">
                    <span class="fas fa-boxes"></span>
                    {{ __('Add Subproduct') }}
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.products.show', $product) }}">
                    <span class="fas fa-arrow-left"></span>
                    {{ __('Back to Product') }}
                </a>
            </div>
            <div class="col-md-6">
                <x-listpage-count :text="__('app.subproduct_count')" :value="$subproducts->total()" />
            </div>
        </div>
        <x-listpage-searchbox class="mb-3" name="search_value" />
        <x-listpage-table>
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">
                            <x-listpage-sorter column="id" label="#" />
                        </th>
                        <th style="width: 25%">
                            <x-listpage-sorter column="name" :label="__('Name')" />
                        </th>
                        <th>
                            <x-listpage-sorter column="description" :label="__('Description')" />
                        </th>
                        <th class="text-end" style="width: 12%">
                            <x-listpage-sorter column="price" :label="__('Price')" />
                        </th>
                        <th class="text-center" style="width: 10%">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(subproduct, index) in data.data" x-bind:key="subproduct.id">
                        <tr>
                            <td class="text-center" x-text="data.meta.from + index"></td>
                            <td x-text="subproduct.name"></td>
                            <td x-text="subproduct.description || '-'"></td>
                            <td class="text-end" x-text="'RM ' + Number(subproduct.price).toFixed(2)"></td>
                            <td class="text-center">
                                <div class="mx-auto" style="width: 100px">
                                    <a class="btn btn-outline-dark btn-xs" x-bind:href="zroute('admin.subproducts.show', subproduct.id)">
                                        <span class="far fa-eye fa-fw"></span>
                                    </a>
                                    <a class="btn btn-outline-dark btn-xs" x-bind:href="zroute('admin.subproducts.edit', subproduct.id)">
                                        <span class="far fa-edit fa-fw"></span>
                                    </a>
                                    <button class="btn btn-outline-danger btn-xs" x-on:click="remove(subproduct)">
                                        <span class="far fa-trash-alt fa-fw"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!loading && data.data && data.data.length === 0">
                        <td colspan="5" class="text-center text-muted py-4">
                            {{ __('No subproducts found for this product.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-listpage-table>
        <div>{{ $subproducts->links() }}</div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Object.assign(window.PageData, {
            subproducts: @json($subproducts),
            product: @json($product),
        });
    </script>
    <script type="text/javascript" src="{{ asset('views/admin/subproducts/index.js') }}"></script>
@endpush
