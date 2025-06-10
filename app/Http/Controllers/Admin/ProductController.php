<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductServiceInterface;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;


class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function products()
    {
        $products = $this->productService->getAll();
        return view('admin.products.products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        if ($request->hasFile('product_image')) {

            $profileImage = $request->file('product_image');
        }

        $data = $request->validated();
        $data['content'] = array_map('trim', explode('|', $data['content']));
        $data['for_whom'] = array_map('trim', explode('|', $data['for_whom']));


        if ($this->productService->create($data, $profileImage ?? null)) {
            return redirect()->route('admin.products')->with('success', 'Product created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create product. Please try again.');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = $this->productService->getById($id);
        $product->content = implode('|', $product->content);
        $product->for_whom = implode('|', $product->for_whom);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request)
    {
        $product = $this->productService->getById($request->product_id);
        if ($request->hasFile('product_image')) {
            $productImage = $request->file('product_image');
        }

        $data = $request->validated();
        $data['content'] = array_map('trim', explode('|', $data['content']));
        $data['for_whom'] = array_map('trim', explode('|', $data['for_whom']));

        if ($this->productService->update($request->product_id, $data, $productImage ?? null)) {
            return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update product. Please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->productService->destroy($id)) {
            return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete product. Please try again.');
    }
}
