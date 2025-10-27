<?php

namespace App\Http\Controllers;

use App\Models\AsignaProductoProveedor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAsignacionRequest;
use App\Http\Requests\UpdateAsignacionRequest;
use App\Models\Producto;
use App\Models\Proveedor;



class AsignaProductoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asignaciones.viewAny')->only('index');
        $this->middleware('permission:asignaciones.view')->only('show');
        $this->middleware('permission:asignaciones.create')->only(['create','store']);
        $this->middleware('permission:asignaciones.update')->only(['edit','update']);
        $this->middleware('permission:asignaciones.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $q   = trim((string)$request->get('q'));
        $pid = $request->get('producto');
        $vid = $request->get('proveedor');
        $per = (int)$request->get('per_page', 15);

        $asignaciones = AsignaProductoProveedor::query()
            ->with(['producto:id,nombre', 'proveedor.persona:id,nombre,ap,am'])
            ->when($q, fn($x) => $x->where('sku_proveedor', 'like', "%{$q}%"))
            ->when($pid, fn($x) => $x->where('productos_id', $pid))
            ->when($vid, fn($x) => $x->where('proveedores_id', $vid))
            ->orderByDesc('id')
            ->paginate(in_array($per, [10,15,25,50]) ? $per : 15)
            ->withQueryString();

        $productos   = Producto::orderBy('nombre')->get(['id','nombre']);
        $proveedores = Proveedor::with('persona:id,nombre,ap,am')->get(['id','personas_id']);

        return view('admin.asignaciones.index', compact(
            'asignaciones','productos','proveedores','q','pid','vid','per'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $preProducto  = $request->get('producto');
        $preProveedor = $request->get('proveedor');

        $productos   = Producto::orderBy('nombre')->get(['id','nombre']);
        $proveedores = Proveedor::with('persona:id,nombre,ap,am')->get(['id','personas_id']);

        return view('admin.asignaciones.create', compact('productos','proveedores','preProducto','preProveedor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsignacionRequest $request)
    {
        $asignacion = AsignaProductoProveedor::create($request->validated());

        return redirect()
            ->route('admin.asignaciones.show', $asignacion)
            ->with('status', 'Asignación creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(AsignaProductoProveedor $asignacion)
    {
        $asignacion->load(['producto:id,nombre','proveedor.persona:id,nombre,ap,am']);
        return view('admin.asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsignaProductoProveedor $asignacion)
    {
        $productos   = Producto::orderBy('nombre')->get(['id','nombre']);
        $proveedores = Proveedor::with('persona:id,nombre,ap,am')->get(['id','personas_id']);

        return view('admin.asignaciones.edit', compact('asignacion','productos','proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsignacionRequest $request, AsignaProductoProveedor $asignacion)
    {
        $asignacion->update($request->validated());

        return redirect()
            ->route('admin.asignaciones.show', $asignacion)
            ->with('status', 'Asignación actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsignaProductoProveedor $asignacion)
    {
        $asignacion->delete();

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('status', 'Asignación eliminada');
    }
}
