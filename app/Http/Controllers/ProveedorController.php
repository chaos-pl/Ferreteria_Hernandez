<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Persona;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:proveedores.viewAny')->only('index');
        $this->middleware('permission:proveedores.view')->only('show');
        $this->middleware('permission:proveedores.create')->only(['create','store']);
        $this->middleware('permission:proveedores.update')->only(['edit','update']);
        $this->middleware('permission:proveedores.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $estado = $request->get('estado');
        $perPage = (int)$request->get('per_page', 10);
        $perPage = in_array($perPage,[5,10,15,25,50]) ? $perPage : 10;

        $proveedores = Proveedor::query()
            ->with('persona')
            ->when($q, fn($x)=>$x->whereHas('persona', function($p) use($q){
                $p->where('nombre','like',"%{$q}%")
                    ->orWhere('ap','like',"%{$q}%")
                    ->orWhere('am','like',"%{$q}%")
                    ->orWhere('telefono','like',"%{$q}%");
            }))
            ->when(in_array($estado,['activo','inactivo']), fn($x)=>$x->where('estado',$estado))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.proveedores.index', compact('proveedores','q','estado','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = Persona::orderBy('nombre')->orderBy('ap')->limit(200)->get(['id','nombre','ap','am']);
        return view('admin.proveedores.create', compact('personas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProveedorRequest $request)
    {
        Proveedor::create($request->validated());
        return redirect()->route('admin.proveedores.index')->with('status','Proveedor creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        $proveedor->load('persona');
        return view('admin.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        $personas = Persona::orderBy('nombre')->orderBy('ap')->limit(200)->get(['id','nombre','ap','am']);
        return view('admin.proveedores.edit', compact('proveedor','personas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedor $proveedor)
    {
        $proveedor->update($request->validated());
        return redirect()->route('admin.proveedores.index')->with('status','Proveedor actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete(); // soft delete
        return redirect()->route('admin.proveedores.index')->with('status','Proveedor eliminado');
    }
}
