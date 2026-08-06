<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchColumn = $request->query('search_column');
        $searchValue = $request->query('search_value');
        $sort = $request->query('sort');

        // Get the query builder
        $query = Product::query()->withCount('subproducts');

        // Search - Standard Laravel way
        if ($searchValue && in_array($searchColumn, ['name', 'description'])) {
            $query->where($searchColumn, 'like', '%' . $searchValue . '%');
        }

        // Sort - Standard Laravel way
        $validSortColumns = ['id', 'name', 'description', 'price', 'created_at'];

        if ($sort && in_array(ltrim($sort, '-'), $validSortColumns)) {
            $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $column = ltrim($sort, '-');
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Get the result
        $columns = ['id', 'name', 'description', 'price', 'created_at', 'updated_at'];

        $products = $query->select($columns)
            ->paginate(25);

        return $request->wantsJson()
            ? $products
            : view('admin.products.index', compact('products'));
    }

    /*to show create form*/
    public function create()
    {
        return $this->edit(new Product());
    }

    /*add new product*/
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect(route('admin.products.index'))
            ->with('item_id', $product->id)
            ->withSuccessNotification(__('Product :name has been added successfully.', ['name' => e($product->name)]));
    }

    /*show the product details*/
    public function show(Request $request, Product $product)
    {
        $product->loadCount('subproducts');

        return $request->wantsJson()
            ? $product
            : view('admin.products.show', compact('product'));
    }

    /*to show the edit form*/
    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    /*to update product*/
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->backOrIndex()
            ->with('item_id', $product->id)
            ->withSuccessNotification(__('Product :name has been updated successfully.', ['name' => e($product->name)]));
    }

    public function destroy(Request $request, Product $product)
    {
        $name = $product->name;
        $product->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Product :name has been deleted successfully.', ['name' => e($name)]),
            ]);
        }

        return redirect(route('admin.products.index'))
            ->withSuccessNotification(__('Product :name has been deleted successfully.', ['name' => e($name)]));
    }
}
