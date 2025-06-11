<?php
namespace App\Services;
use App\Services\Interfaces\ProductServiceInterface;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ProductService implements ProductServiceInterface
{
    public function getAll(): Collection
    {
        return Product::all();
    }

    public function getById(int $id): Product
    {
        return Product::find($id);
    }

    public function create(array $data, $profileImage = null): Product
    {
        $product = Product::create($data);
        if ($profileImage) {
            $product->addMedia($profileImage)->toMediaCollection('product_images');
        }
        return $product;
    }

    public function update(int $id, array $data, $productImage = null): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        if ($productImage) {
            if ($product->hasMedia('product_images')) {
                $product->clearMediaCollection('product_images');
            }

            $product->addMedia($productImage)->toMediaCollection('product_images');
        }
        return $product;
    }

      public function destroy(int $id): bool
    {
        $product = Product::findOrFail($id);

        return $product->delete();
    }


}
