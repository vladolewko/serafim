<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('site.index', compact('products'));
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

        return view('site.product', compact('product'));
    }
}
