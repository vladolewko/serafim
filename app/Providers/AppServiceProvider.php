<?php

namespace App\Providers;


use App\Services\BannerService;
use App\Services\Interfaces\BannerServiceInterface;
use App\Services\WayForPayService;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\ProductService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(BannerServiceInterface::class, BannerService::class);
//        $this->app->bind(WayForPayService::class, WayForPayService::class);
//        $this->app->bind(OrderService::class, OrderService::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
