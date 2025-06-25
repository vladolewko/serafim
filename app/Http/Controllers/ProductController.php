<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Interfaces\ProductServiceInterface;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }


    public function getAll()
    {
        $products = $this->productService->getAll();
        $applyings = Product::getApplyingOptions();

        $productsForApplying = $products->groupBy('applying.value')
            ->map(fn($group) => $group->first());

//        dd($productsForApplying);


        return view('site.index', [
            'productsChunks' => $products->chunk(3),
            'products' => $products,
            'applyings' => $applyings,
            'productsForApplying' => $productsForApplying
        ]);
    }
    /**
     * Display a product by its ID.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function getById($id)
    {
        $product = $this->productService->getById($id);

        $products = Product::whereNot('id', $id)->get();
        //dd($products);
        return view('site.product', [
            'productsChunks' => $products->chunk(3),
            'products' => $products,
            'product' => $product,
        ]);
    }
}
