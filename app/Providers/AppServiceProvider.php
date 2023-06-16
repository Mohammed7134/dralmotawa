<?php

namespace App\Providers;

use App\Models\Subscriber;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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
        $path = public_path() . '/json/categories.json';
        $file = file_get_contents($path);
        $categories = json_decode($file, true);
        $numberOfSubscribers = Subscriber::count();
        view()->share(compact('categories', 'numberOfSubscribers'));
        Paginator::useBootstrap();
    }
}
