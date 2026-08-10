<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
    * Display a listing of the products.
    */
    public function index(Request $request)
    {
        $searchColumn = $request->query('search_column');
        $searchValue = $request->query('search_value');
        $sort = $request->query('sort');

        $query = Product::query();

        // Search
        $validSearchColumns = ['name', 'description'];
        $query->searchWildcard($searchValue, in_array($searchColumn, $validSearchColumns) ? $searchColumn : null);

        // Sort
        $validSortColumns = ['id', 'name', 'description', 'price', 'created_at'];
        $query->resolveSortString($sort, 'id', 'desc', $validSortColumns);

        $columns = ['id', 'name', 'description', 'price', 'created_at', 'updated_at'];

        $products = $query->select($columns)
            ->withCount('subProducts')
            ->paginate(25)
            ->withIndexPathAndQueryString();

        return $request->wantsJson()
            ? $products
            : view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return $this->edit(new Product());
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect(route('admin.products.index'))
            ->with('item_id', $product->id)
            ->withSuccessNotification(__('Product :name has been added successfully.', ['name' => e($product->name)]));
    }

    /**
     * Show the product details.
     */
    public function show(Request $request, Product $product)
    {
        $product->loadCount('subProducts');

        return $request->wantsJson()
            ? $product
            : view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->backOrIndex()
            ->with('item_id', $product->id)
            ->withSuccessNotification(__('Product :name has been updated successfully.', ['name' => e($product->name)]));
    }
    /**
      * Remove the specified product from storage
      */
      public function destroy(Request $request, Product $product)
    {
        $product->delete();

        return $this->index($request);
    }
}
