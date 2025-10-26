<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUnidadRequest;
use App\Http\Requests\UpdateUnidadRequest;

class UnidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:unidades.viewAny')->only('index');
        $this->middleware('permission:unidades.view')->only('show');
        $this->middleware('permission:unidades.create')->only(['create','store']);
        $this->middleware('permission:unidades.update')->only(['edit','update']);
        $this->middleware('permission:unidades.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $perPage = (int)$request->get('per_page', 10);
        $perPage = in_array($perPage,[5,10,15,25,50]) ? $perPage : 10;

        $unidades = Unidad::query()
            ->when($q, fn($x)=>$x->where(function($y) use($q){
                $y->where('nombre','like',"%{$q}%")
                    ->orWhere('abreviatura','like',"%{$q}%");
            }))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.unidades.index', compact('unidades','q','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.unidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnidadRequest $request)
    {
        Unidad::create($request->validated());
        return redirect()->route('admin.unidades.index')->with('status','Unidad creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unidad $unidad)
    {
        $unidad->loadCount('productos');
        return view('admin.unidades.show', compact('unidad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unidad $unidad)
    {
        return view('admin.unidades.edit', compact('unidad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnidadRequest $request, Unidad $unidad)
    {
        $unidad->update($request->validated());
        return redirect()->route('admin.unidades.index')->with('status','Unidad actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unidad $unidad)
    {
        $unidad->delete(); // soft delete
        return redirect()->route('admin.unidades.index')->with('status','Unidad eliminada');
    }
}
