<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\AsignaProductoProveedorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Shop\CarritoController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\Shop\CheckoutController;

/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA – LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('landing');


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| RUTA DEL DASHBOARD (ADMIN)
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('home');


/*
|--------------------------------------------------------------------------
| ADMIN PANEL (solo usuarios autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('promociones', PromocionController::class)
        ->parameters(['promociones' => 'promocion']);

    Route::resource('categorias', CategoriaController::class);
    Route::resource('municipios', MunicipioController::class);
    Route::resource('direcciones', DireccionController::class)
        ->parameters(['direcciones' => 'direccion']);

    Route::resource('marcas', MarcaController::class)
        ->parameters(['marcas' => 'marca']);

    Route::resource('personas', PersonaController::class)
        ->parameters(['personas' => 'persona']);

    Route::resource('unidades', UnidadController::class)
        ->parameters(['unidades' => 'unidad']);

    Route::resource('proveedores', ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor']);

    Route::resource('productos', ProductoController::class)
        ->parameters(['productos' => 'producto']);

    Route::resource('asignaciones', AsignaProductoProveedorController::class)
        ->parameters(['asignaciones' => 'asignacion']);

    Route::resource('users', UserController::class)
        ->parameters(['users' => 'user']);

    Route::resource('compras', CompraController::class)
        ->parameters(['compras' => 'compra']);

    Route::resource('ventas', VentaController::class)
        ->parameters(['ventas' => 'venta']);

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:roles.viewAny')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::middleware('permission:roles.create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::middleware('permission:roles.create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::middleware('permission:roles.view')->get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::middleware('permission:roles.update')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::middleware('permission:roles.update')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::middleware('permission:roles.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});


/*
|--------------------------------------------------------------------------
| CARRITO Y TIENDA (solo usuarios logueados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function(){

    Route::get('/carrito', [CarritoController::class, 'index'])->name('shop.carrito');
    Route::post('/carrito/add', [CarritoController::class, 'add'])->name('shop.carrito.add');
    Route::patch('/carrito/{item}/update', [CarritoController::class, 'update'])->name('shop.carrito.update');
    Route::delete('/carrito/{item}/remove', [CarritoController::class, 'remove'])->name('shop.carrito.remove');
    Route::delete('/carrito/clear', [CarritoController::class, 'clear'])->name('shop.carrito.clear');
    Route::post('/carrito/checkout', [CarritoController::class, 'checkout'])->name('shop.carrito.checkout');

    Route::get('/tienda', [ShopController::class, 'catalogo'])->name('shop.catalogo');
});


/*
|--------------------------------------------------------------------------
| CHECKOUT SOLO CLIENTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'cliente'])->group(function() {

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('shop.checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('shop.checkout.process');

});
