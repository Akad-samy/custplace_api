<?php

namespace App\Providers;

use App\Http\Repositories\ProductInterface;
use App\Http\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ProductInterface::class, ProductRepository::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
