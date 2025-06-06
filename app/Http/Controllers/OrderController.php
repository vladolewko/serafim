<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\NovaPostService;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Area;
use App\Models\District;

class OrderController extends Controller
{
    protected $novaPostService;
    protected $productService;

    public function __construct(NovaPostService $novaPostService, ProductServiceInterface $productService)
    {
        $this->novaPostService = $novaPostService;
        $this->productService = $productService;
    }

    /**
     * Показати форму створення замовлення
     */
    public function create(Request $request)
    {
        // $product = $this->productService->getById($productId);
        // $quantity = $request->get('quantity', 1);

        // if (!$quantity || $quantity < 1) {
        //     return redirect()->route('products.index')->withErrors(['error' => 'Кількість товару має бути більше 0']);
        // }
        // if($product) {
        //     $request->session()->put('product_id', $productId);
        // } else {
        //     return redirect()->route('products.index')->withErrors(['error' => 'Товар не знайдено']);
        // }

        return view('orders.create');
    }
}