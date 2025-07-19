<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBannerRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\Interfaces\BannerServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\KeyCrmService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    protected $bannerService;

    public function __construct(BannerServiceInterface $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /*
     * Getting all products
     */
    public function banners()
    {
        $banners = $this->bannerService->getAll();
        return view('admin.banners.banners', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBannerRequest $request)
    {
//        dd($request->all());

        $data = $request->validated();


        $banner = $this->bannerService->create($data);
        if ($banner) {

            return redirect()->route('admin.banners')->with('success', 'Банер успішно створено.');
        }
        return redirect()->back()->with('error', 'Помилка при створенні банеру. Будь ласка, спробуйте ще раз.');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = $this->bannerService->getById($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request)
    {
//        dd('dd');
        $data = $request->validated();

        $banner = $this->bannerService->update($request->banner_id, $data);
        if ($banner) {
            return redirect()->route('admin.banners')->with('success', 'Банер успішно оновлено.');
        }
        return redirect()->back()->with('error', 'Помилка при оновленні банеру. Будь ласка, спробуйте ще раз.');
    }

    /*
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ( $this->bannerService->destroy($id)) {
            return redirect()->route('admin.banners')->with('success', 'Банер успішно видалено.');
        }
        return redirect()->back()->with('error', 'Помилка при видаленні банеру. Будь ласка, спробуйте ще раз.');
    }
}
