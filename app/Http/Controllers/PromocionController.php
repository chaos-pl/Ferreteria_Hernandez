<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Producto;
use App\Http\Requests\StorePromocionRequest;
use App\Http\Requests\UpdatePromocionRequest;

class PromocionController extends Controller
{
    public function index()
    {
        $promos = Promocion::orderByDesc('id')->paginate(10);
        return view('admin.promociones.index', compact('promos'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('admin.promociones.create', compact('productos'));
    }

    public function store(StorePromocionRequest $request)
    {
        // Crear la promo
        $promo = Promocion::create(
            $request->only(['nombre','descuento','fecha_inicio','fecha_fin'])
        );

        // Sincronizar productos relacionados
        if ($request->filled('productos')) {
            $promo->productos()->sync($request->productos);
        }

        return redirect()
            ->route('admin.promociones.index')
            ->with('status', 'Promoción creada correctamente.');
    }

    public function edit(Promocion $promocion)
    {
        $productos = Producto::orderBy('nombre')->get();
        $promocion->load('productos');

        return view('admin.promociones.edit', compact('promocion', 'productos'));
    }

    public function update(UpdatePromocionRequest $request, Promocion $promocion)
    {
        // Actualizar campos básicos
        $promocion->update(
            $request->only(['nombre','descuento','fecha_inicio','fecha_fin'])
        );

        // Actualizar relación con productos
        $promocion->productos()->sync($request->productos ?? []);

        return redirect()
            ->route('admin.promociones.index')
            ->with('status', 'Promoción actualizada correctamente.');
    }

    public function destroy(Promocion $promocion)
    {
        // Quitar relaciones
        $promocion->productos()->detach();

        // Eliminar promoción
        $promocion->delete();

        return redirect()
            ->route('admin.promociones.index')
            ->with('status', 'Promoción eliminada correctamente.');
    }
}
