<?php
namespace App\Services;
use App\Models\Banner;
use App\Services\Interfaces\BannerServiceInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class BannerService implements BannerServiceInterface
{
    public function getAll(): Collection
    {
        return Banner::all();
    }

    public function getById(int $id): Banner
    {
        return Banner::find($id);
    }

    public function create(array $data): Banner|null
    {
        $imageFile = $data['banner_image'] ?? null;
        unset($data['banner_image']);

        $banner = Banner::create($data);

        if ($imageFile) {
            if ($banner->hasMedia('banner_images')) {
                $banner->clearMediaCollection('banner_images');
            }

            $banner->addMedia($imageFile)->toMediaCollection('banner_images');
        }
        if(!$banner) {
            return null;
        }
        return $banner;
    }

    public function update(int $id, array $data): Banner|null
    {
        try {
            $banner = Banner::findOrFail($id);

            $imageFile = $data['banner_image'] ?? null;
            unset($data['banner_image']);

            $banner->update($data);

            if ($imageFile) {
                if ($banner->hasMedia('banner_images')) {
                    $banner->clearMediaCollection('banner_images');
                }

                $banner->addMedia($imageFile)->toMediaCollection('banner_images');
            }

            return $banner;

        } catch (\Exception $exception) {
            return null;
        }
    }

      public function destroy(int $id): bool
    {
        $banner = Banner::findOrFail($id);

        return $banner->delete();
    }


}
