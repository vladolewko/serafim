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

    public function create(array $data): Product|null
    {
        $imageFile = $data['product_image'] ?? null;
        unset($data['product_image']);

        $product = Product::create($data);

        if ($imageFile) {
            if ($product->hasMedia('product_images')) {
                $product->clearMediaCollection('product_images');
            }

            $product->addMedia($imageFile)->toMediaCollection('product_images');
        }
        if(!$product) {
            return null;
        }
        return $product;
    }

    public function update(int $id, array $data): Product|null
    {
        try {
            $product = Product::findOrFail($id);

            $imageFile = $data['product_image'] ?? null;
            unset($data['product_image']);

            $product->update($data);

            if ($imageFile) {
                if ($product->hasMedia('product_images')) {
                    $product->clearMediaCollection('product_images');
                }

                $product->addMedia($imageFile)->toMediaCollection('product_images');
            }

            return $product;

        } catch (\Exception $exception) {
//            \Log::error('Product update failed: ' . $exception->getMessage());
            return null;
        }
    }

      public function destroy(int $id): bool
    {
        $product = Product::findOrFail($id);

        return $product->delete();
    }


}
