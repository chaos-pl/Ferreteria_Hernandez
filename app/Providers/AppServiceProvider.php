<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;   // ← IMPORTA EL FACADE CORRECTO
use Illuminate\Support\Facades\Auth;   // ← si usas Auth adentro
use Illuminate\Support\Facades\Schema; // ← opcional para proteger en migraciones
use App\Models\Carrito;

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
        //
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $count = 0; $mini = null;

            if (Auth::check()) {
                $mini = Carrito::with('items.producto')
                    ->abierto()
                    ->where('users_id', Auth::id())
                    ->first();

                $count = $mini?->items->sum('cantidad') ?? 0;
            }

            $view->with('cartCount', $count)
                ->with('cartMini', $mini);
        });
    }
}
