@extends('layouts.admin')

@section('title', __('View Subproduct'))

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Sub Product Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <td>
                                    <a href="{{ route('admin.products.show', $subproduct->product) }}">
                                        {{ $subproduct->product->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <td>{{ $subproduct->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Description') }}</th>
                                <td>{{ $subproduct->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Price') }}</th>
                                <td>RM {{ number_format($subproduct->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>{{ $subproduct->created_at }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Updated At') }}</th>
                                <td>{{ $subproduct->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.products.subproducts.index', $subproduct->product) }}" class="btn btn-secondary">
                        <span class="fas fa-arrow-left"></span> {{ __('Back to Subproducts') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

