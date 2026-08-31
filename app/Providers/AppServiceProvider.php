<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tag;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('tags')) {
                    $tags_navbar = Tag::where('name', '!=', 'S1 Informatika')->orderBy('name', 'asc')->get();
                    $view->with('tags_navbar', $tags_navbar);
                } else {
                    $view->with('tags_navbar', collect());
                }
            } catch (\Exception $e) {
                $view->with('tags_navbar', collect());
            }
        });
    }
}
