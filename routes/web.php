<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProveedorController;



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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
