<?php

use Illuminate\Support\Facades\Route;
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






///Redireccionamiento al landing page al logearte
Route::get('/', function () {
    return view('landing_page');
});


///Rutas de categorias
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){
    Route::resource('categorias', CategoriaController::class);
});

///Rutas de Municipios
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('municipios', MunicipioController::class);
});

//Ruta de Direcciones
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('direcciones', \App\Http\Controllers\DireccionController::class)
        ->parameters(['direcciones' => 'direccion']);
});

//Ruta de Marcas
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('marcas', MarcaController::class)
        ->parameters(['marcas' => 'marca']);
});

//Ruta de Personas
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('personas', PersonaController::class)
        ->parameters(['personas' => 'persona']); // explícito, por claridad
});

//Ruta de Unidades
use App\Http\Controllers\UnidadController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('unidades', UnidadController::class)
        ->parameters(['unidades' => 'unidad']);
});

//Ruta de Proveedores
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('proveedores', ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor']);
});

///Ruta de Productos
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class)
        ->parameters(['productos' => 'producto']);
});

//Ruta de asignaProductoProveedor
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('asignaciones', AsignaProductoProveedorController::class)
        ->parameters(['asignaciones' => 'asignacion']);
});

//Ruta de users
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)
        ->parameters(['users' => 'user']);
});

//Ruta de Compras
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('compras', CompraController::class)
        ->parameters(['compras' => 'compra']);
});

//Ruta de Ventas
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('ventas', VentaController::class)
            ->parameters(['ventas' => 'venta']); // {venta}

    });
});

//Ruta roles
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {

        // CRUD de Roles protegido por permisos
        Route::middleware('permission:roles.viewAny')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::middleware('permission:roles.create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::middleware('permission:roles.create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::middleware('permission:roles.view')->get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::middleware('permission:roles.update')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::middleware('permission:roles.update')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::middleware('permission:roles.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    });
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
