<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductServiceInterface;
use App\Models\Product;
use App\Http\Requests\Product\CreateProductRequest;


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
        // dd($request->all());
        $data = $request->validated();
        // dd($data);
        $data['content'] = array_map('trim', explode(',', $data['content']));
        $data['for_whom'] = array_map('trim', explode(',', $data['for_whom']));
        // dd($data);

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
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Logic to update a specific product
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Logic to delete a specific product
    }
}
