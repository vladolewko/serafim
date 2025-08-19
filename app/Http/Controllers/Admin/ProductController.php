<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ProductServiceInterface;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\KeyCrmService;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    protected $productService;
    protected $keycrmService;

    public function __construct(ProductServiceInterface $productService, KeycrmService $keycrmService)
    {
        $this->productService = $productService;
        $this->keycrmService = $keycrmService;
    }

    /*
     * Getting all products
     */
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
        $applyings = Product::getApplyingOptions();

        return view('admin.products.create', compact('applyings'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $data['content'] = array_map('trim', explode('|', $data['content']));
        $data['for_whom'] = array_map('trim', explode('|', $data['for_whom']));

        $product = $this->productService->create($data);

        if ($product) {
            try {
                $syncResult = $this->keycrmService->syncProduct($product);

                if ($syncResult) {
                    return redirect()->route('admin.products')->with('success', 'Товар успішно створено та синхронізовано з KeyCRM.');
                } else {
                    Log::warning('Product created locally but KeyCRM sync failed', [
                        'product_id' => $product->id
                    ]);
                    return redirect()->route('admin.products')->with('warning', 'Товар створено, але не вдалося синхронізувати з KeyCRM. Спробуйте синхронізувати пізніше.');
                }
            } catch (\Exception $e) {
                Log::error('KeyCRM sync failed during product creation', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->route('admin.products')->with('warning', 'Товар створено, але синхронізація з KeyCRM не вдалася: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Помилка при створенні продукту. Будь ласка, спробуйте ще раз.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $applyings = Product::getApplyingOptions();
        $product = $this->productService->getById($id);
        $product->content = implode('|', $product->content);
        $product->for_whom = implode('|', $product->for_whom);
        return view('admin.products.edit', compact('product', 'applyings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request)
    {
        $data = $request->validated();

        $data['content'] = array_map('trim', explode('|', $data['content']));
        $data['for_whom'] = array_map('trim', explode('|', $data['for_whom']));

        $product = $this->productService->update($request->product_id, $data);

        if ($product) {
            try {
                $syncResult = $this->keycrmService->syncProduct($product);

                if ($syncResult) {
                    return redirect()->route('admin.products')->with('success', 'Товар успішно оновлено та синхронізовано з KeyCRM.');
                } else {
                    Log::warning('Product updated locally but KeyCRM sync failed', [
                        'product_id' => $product->id
                    ]);
                    return redirect()->route('admin.products')->with('warning', 'Товар оновлено, але не вдалося синхронізувати з KeyCRM.');
                }
            } catch (\Exception $e) {
                Log::error('KeyCRM sync failed during product update', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->route('admin.products')->with('warning', 'Товар оновлено, але синхронізація з KeyCRM не вдалася: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Помилка при оновленні продукту. Будь ласка, спробуйте ще раз.');
    }
    /*
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ( $this->productService->destroy($id)) {
            return redirect()->route('admin.products')->with('success', 'Товар успішно видалено.');
        }
        return redirect()->back()->with('error', 'Помилка при видаленні товару. Будь ласка, спробуйте ще раз.');
    }
}
