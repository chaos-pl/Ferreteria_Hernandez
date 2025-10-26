<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use Illuminate\Http\Request;
use App\Models\Municipio;
use App\Http\Requests\StoreDireccionRequest;
use App\Http\Requests\UpdateDireccionRequest;
class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:direcciones.viewAny')->only('index');
        $this->middleware('permission:direcciones.view')->only('show');
        $this->middleware('permission:direcciones.create')->only(['create','store']);
        $this->middleware('permission:direcciones.update')->only(['edit','update']);
        $this->middleware('permission:direcciones.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage,[5,10,15,25,50]) ? $perPage : 10;

        $direcciones = Direccion::query()
            ->with('municipio')
            ->when($q, fn($x)=>$x->where(function($y) use($q){
                $y->where('calle','like',"%{$q}%")
                    ->orWhere('colonia','like',"%{$q}%");
            }))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.direcciones.index', compact('direcciones','q','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $municipios = class_exists(Municipio::class)
            ? Municipio::orderBy('nombre')->limit(200)->get(['id','nombre'])
            : collect(); // vacío si no hay tabla municipios

        return view('admin.direcciones.create', compact('municipios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDireccionRequest $request)
    {
        Direccion::create($request->validated());
        return redirect()->route('admin.direcciones.index')->with('status','Dirección creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Direccion $direccion)
    {
        $direccion->load('municipio');
        return view('admin.direcciones.show', compact('direccion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Direccion $direccion)
    {
        $municipios = class_exists(Municipio::class)
            ? Municipio::orderBy('nombre')->limit(200)->get(['id','nombre'])
            : collect();

        return view('admin.direcciones.edit', compact('direccion','municipios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDireccionRequest $request, Direccion $direccion)
    {
        $direccion->update($request->validated());
        return redirect()->route('admin.direcciones.index')->with('status','Dirección actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Direccion $direccion)
    {
        $direccion->delete(); // soft delete
        return redirect()->route('admin.direcciones.index')->with('status','Dirección eliminada');
    }
}
