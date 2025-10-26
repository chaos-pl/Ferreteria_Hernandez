<?php

namespace App\Providers;

use App\Models\Categoria;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        View::composer('layouts.dashboard', function ($view) {
            $categorias = Cache::remember('sidebar:categorias', 60, function () {
                return Categoria::orderBy('nombre')->get(['id','nombre']);
            });

            $view->with('categoriasSidebar', $categorias);
        });
    }
}
