<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubProductRequest;
use App\Models\Product;
use App\Models\SubProduct;
use Illuminate\Http\Request;

class SubProductController extends Controller
{
    /**
     * Display a listing of the subproducts for a product.
     */
    public function index(Product $product)
    {
        $subproducts = $product->subProducts()
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('admin.subproducts.index', compact('product', 'subproducts'));
    }

    /**
     * Show the form for creating a new subproduct.
     */
    public function create(Product $product)
    {
        $subproduct = new SubProduct();
        return view('admin.subproducts.form', compact('product', 'subproduct'));
    }

    /**
     * Store a newly created subproduct in storage.
     */
    public function store(SubProductRequest $request, Product $product)
    {
        $subproduct = $product->subProducts()->create($request->validated());

        return redirect(route('admin.products.subproducts.index', $product))
            ->withSuccessNotification(__('Subproduct :name has been added successfully.', ['name' => e($subproduct->name)]));
    }

    /**
     * Display the specified subproduct.
     */
    public function show(SubProduct $subproduct)
    {
        $subproduct->load('product');
        return view('admin.subproducts.show', compact('subproduct'));
    }

    /**
     * Show the form for editing the specified subproduct.
     */
    public function edit(SubProduct $subproduct)
    {
        $product = $subproduct->product;
        return view('admin.subproducts.form', compact('product', 'subproduct'));
    }

    /**
     * Update the specified subproduct in storage.
     */
    public function update(SubProductRequest $request, SubProduct $subproduct)
    {
        $subproduct->update($request->validated());

        return redirect(route('admin.products.subproducts.index', $subproduct->product))
            ->withSuccessNotification(__('Subproduct :name has been updated successfully.', ['name' => e($subproduct->name)]));
    }

    /**
     * Remove the specified subproduct from storage.
     */
    public function destroy(SubProduct $subproduct)
    {
        $subproduct->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->withSuccessNotification(__('Subproduct deleted successfully.'));
    }
}
