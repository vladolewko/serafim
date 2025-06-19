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
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
         $product = $this->productService->getById($productId);

//         dd($product);
         if ($product && $quantity && $quantity >0) {
             session()->put('cart', ['product' => $product, 'quantity' => $quantity, 'total' => $product->price * $quantity]);
             $cart = session()->get('cart');

             return view('site.orders.create', compact('cart'));

         }

        return back()->with('error', 'Помилка');
    }

    public function paymentSuccess()
    {

    }
}
