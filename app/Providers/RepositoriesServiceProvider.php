<?php

namespace App\Providers;

use App\Http\Repositories\ProductInterface;
use App\Http\Repositories\ProductRepository;

use App\Interfaces\ReviewInterface;
use App\Interfaces\UserInterface;

use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;

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
        $this->app->bind(ReviewInterface::class, ReviewRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
    }
}
