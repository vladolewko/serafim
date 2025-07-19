<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Interfaces\BannerServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;

class ProductController extends Controller
{
    protected $productService;
    protected $bannerService;
    public function __construct(ProductServiceInterface $productService, BannerServiceInterface $bannerService)
    {
        $this->productService = $productService;
        $this->bannerService = $bannerService;
    }

    /*
     * getting all products
     */
    public function getAll()
    {
        $products = $this->productService->getAll();
        $banners = $this->bannerService->getAll();
        $products = $products->concat($banners);
        $applyings = Product::getApplyingOptions();

        $productsForApplying = $products->groupBy('applying.value')
            ->map(fn($group) => $group->first());


        return view('site.index', [
            'productsChunks' => $products->chunk(3),
            'products' => $products,
            'banners' => $banners,
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

        $products = Product::where('id', '!=', $id)->get();
        $banners = $this->bannerService->getAll();

        //dd($products);
        return view('site.product', [
            'productsChunks' => $products->chunk(3),
            'products' => $products,
            'banners' => $banners,
            'product' => $product,
        ]);
    }
}
