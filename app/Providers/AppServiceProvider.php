<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tag;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('navbars.HomeNavbar', function ($view) {
            $tags = Tag::all()->slice(0,8);
            View::share('tags_navbar', $tags);
        });
    }
}
