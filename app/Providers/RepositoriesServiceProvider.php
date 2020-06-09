<?php

namespace App\Providers;

use App\Interfaces\IngredientInterface;
use App\Interfaces\OriginalStoreInterface;
use App\Interfaces\ProductInterface;
use App\Repositories\IngredientRepository;
use App\Repositories\OriginalStoreRepository;
use App\Repositories\ProductRepository;
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
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(OriginalStoreInterface::class, OriginalStoreRepository::class);
        $this->app->bind(IngredientInterface::class, IngredientRepository::class);
    }
}
