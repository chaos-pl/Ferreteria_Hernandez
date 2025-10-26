<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:productos.viewAny')->only(['index']);
        $this->middleware('permission:productos.view')->only(['show']);
        $this->middleware('permission:productos.create')->only(['create','store']);
        $this->middleware('permission:productos.update')->only(['edit','update']);
        $this->middleware('permission:productos.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        //
        $q = $request->get('q');
        $productos = Producto::query()
            ->when($q, fn($x) => $x->where('nombre', 'like', "%{$q}%"))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();


        return view('admin.productos.index', compact('productos','q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        //
        $producto = Producto::create($request->validated());
        return redirect()->route('admin.productos.index')->with('status', 'Producto creado correctamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
        return view('admin.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
        return view('admin.productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        //
        $producto->update($request->validated());
        return redirect()->route('admin.productos.index')->with('status', 'Producto actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('status', 'Producto eliminado');

    }
}
