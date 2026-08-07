@extends('layouts.admin')

@section('title', match (true) {
    request()->is('*/create') => __('Add Product'),
    request()->is('*/edit') => __('Update Product'),
})

@section('content')
    <div class="col-md-12">
        <x-form x-data="Form()" :bind-data="$product">
            <div class="row mb-3">
                <x-label for="name" class="col-md-2">{{ __('Name') }}</x-label>

                <div class="col-md-6">
                    <x-input type="text" name="name" placeholder="{{ __('Enter product\'s name...') }}" autofill />

                    <x-error for="name" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="description" class="col-md-2">{{ __('Description') }}</x-label>

                <div class="col-md-6">
                    <x-textarea name="description" placeholder="{{ __('Enter product\'s description...') }}" autofill />

                    <x-error for="description" />
                </div>
            </div>

            <div class="row mb-3">
                <x-label for="price" class="col-md-2">{{ __('Price (RM)') }}</x-label>

                <div class="col-md-6">
                    <x-input type="number" name="price" step="0.01" min="0" placeholder="0.00" autofill />

                    <x-error for="price" />
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
                formRequest: {!! \App\Http\Requests\ProductRequest::extractJson() !!},
            })
        );
    </script>
@endpush
