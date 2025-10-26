<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\DireccionController;

///Redireccionamiento al landing page al logearte
Route::get('/', function () {
    return view('landing_page');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);
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


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
