<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubproductRequest;
use App\Models\Product;
use App\Models\Subproduct;
use Illuminate\Http\Request;

class SubproductController extends Controller
{
    public function index(Product $product)
    {
        $subproducts = $product->subproducts()
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('admin.subproducts.index', compact('product', 'subproducts'));
    }

    public function create(Product $product)
    {
        return view('admin.subproducts.form', compact('product'));
    }

    public function store(SubproductRequest $request, Product $product)
    {
        $subproduct = $product->subproducts()->create($request->validated());

        return redirect(route('admin.products.subproducts.index', $product))
            ->withSuccessNotification(__('Subproduct :name has been added successfully.', ['name' => e($subproduct->name)]));
    }
    public function show(Subproduct $subproduct)
    {
        $subproduct->load('product');
        return view('admin.subproducts.show', compact('subproduct'));
    }

    public function edit(Subproduct $subproduct)
    {
        $product = $subproduct->product;
        return view('admin.subproducts.form', compact('product', 'subproduct'));
    }

    public function update(SubproductRequest $request, Subproduct $subproduct)
    {
        $subproduct->update($request->validated());

        return redirect(route('admin.products.subproducts.index', $subproduct->product))
            ->withSuccessNotification(__('Subproduct :name has been updated successfully.', ['name' => e($subproduct->name)]));
    }

    public function destroy(Subproduct $subproduct)
    {
        $subproduct->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->withSuccessNotification(__('Subproduct deleted successfully.'));
    }
}
