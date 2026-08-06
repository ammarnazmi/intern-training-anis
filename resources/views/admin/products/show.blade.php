@extends('layouts.admin')

@section('title', __('View Product'))

@section('content')
    <div class="col-md-12">
        {{-- Product Details Card --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Product Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th style="width: 180px">{{ __('Name') }}</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Description') }}</th>
                                <td>{{ $product->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Price') }}</th>
                                <td>RM {{ number_format($product->price, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.products.subproducts.index', $product) }}" class="btn btn-info">
                        <span class="fas fa-boxes"></span>
                        {{ __('Manage Subproducts') }}
                        <span class="badge bg-light text-dark ms-1">{{ $product->subproducts_count ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.products.index', $product) }}" class="btn btn-secondary">
                        <span class="fas fa-arrow-left"></span> {{ __('Back to Products') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
