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
    public function index(Request $request, Product $product)
    {
         $searchColumn = $request->query('search_column');
        $searchValue = $request->query('search_value');
        $sort = $request->query('sort');

        $query = $product->subProducts();

        $validSearchColumns = ['name', 'description'];
        $query->searchWildcard($searchValue, in_array($searchColumn, $validSearchColumns) ? $searchColumn : null);

        $validSortColumns = ['id', 'name', 'description', 'price', 'created_at'];
        $query->resolveSortString($sort, 'id', 'desc', $validSortColumns);

        $subproducts = $query->paginate(25)
            ->withIndexPathAndQueryString();

        return $request->wantsJson()
            ? $subproducts
            : view('admin.subproducts.index', compact('product', 'subproducts'));
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
    public function show(Product $product, SubProduct $subproduct)
    {
        return view('admin.subproducts.show', compact('product', 'subproduct'));
    }

    /**
     * Show the form for editing the specified subproduct.
     */
    public function edit(Product $product, SubProduct $subproduct)
    {
        return view('admin.subproducts.form', compact('product', 'subproduct'));
    }

    /**
     * Update the specified subproduct in storage.
     */
    public function update(SubProductRequest $request,  Product $product, SubProduct $subproduct)
    {
        $subproduct->update($request->validated());

        return redirect(route('admin.products.subproducts.index', $product))
            ->withSuccessNotification(__('Subproduct :name has been updated successfully.', ['name' => e($subproduct->name)]));
    }

    /**
     * Remove the specified subproduct from storage.
     */
    public function destroy(Request $request, Product $product, SubProduct $subproduct)
    {
        $subproduct->delete();

        return $this->index($request, $product);
    }
}
