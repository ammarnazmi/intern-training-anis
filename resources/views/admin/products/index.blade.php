@extends('layouts.admin')

@section('title', __('List of Products'))

@section('content')
    <div class="col-md-12" x-data="ListPage()" data-item-id="@json(session('item_id'))">
        <div class="row mb-3">
            <div class="col-md-6">
                <a class="btn btn-outline-dark" href="{{ route('admin.products.create') }}">
                    <span class="fas fa-box"></span>
                    {{ __('Add Product') }}
                </a>
            </div>
            <div class="col-md-6">
                <x-listpage-count :text="__('app.product_count')" :value="$products->total()" />

                <x-listpage-searchbox class="mb-3" name="search_value" />
             </div>
        </div>
        <x-listpage-table>
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%"><x-listpage-sorter column="id" label="#" /></th>
                        <th style="width: 20%"><x-listpage-sorter column="name" :label="__('Name')" /></th>
                        <th><x-listpage-sorter column="description" :label="__('Description')" /></th>
                        <th class="text-end" style="width: 10%"><x-listpage-sorter column="price" :label="__('Price')" /></th>
                        <th class="text-center" style="width: 10%"><x-listpage-sorter column="sub_products_count" :label="__('Subproducts')" /></th>
                        <th class="text-center" style="width: 15%">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(product, index) in data.data" x-bind:key="product.id">
                        <tr x-bind:id="'item-' + product.id">
                            <td class="text-center" x-text="data.meta.from + index"></td>
                            <td x-text="product.name"></td>
                            <td x-text="product.description || '-'"></td>
                            <td class="text-end" x-text="'RM ' + Number(product.price).toFixed(2)"></td>
                            <td class="text-center" x-text="product.sub_products_count"></td>
                            <td class="text-center">
                                <div class="mx-auto" style="width: 100px">
                                    <a class="btn btn-outline-dark btn-xs" x-bind:href="zroute('admin.products.show', product.id)">
                                        <span class="far fa-eye fa-fw"></span>
                                    </a>
                                    <a class="btn btn-outline-dark btn-xs" x-bind:href="zroute('admin.products.edit', product.id)">
                                        <span class="far fa-edit fa-fw"></span>
                                    </a>
                                    <button class="btn btn-outline-danger btn-xs" x-on:click="remove(product)">
                                        <span class="far fa-trash-alt fa-fw"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </x-listpage-table>

        <div>{{ $products->links() }}</div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Object.assign(window.PageData, {
            products: @json($products),
        });
    </script>
    <script type="text/javascript" src="{{ asset('views/admin/products/index.js') }}"></script>
@endpush
