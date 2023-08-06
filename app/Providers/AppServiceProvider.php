<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Guest;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $categories = Category::all();
        $numberOfSubscribers = Guest::count();
        view()->share(compact('categories', 'numberOfSubscribers'));
        Paginator::useBootstrap();
    }
}
