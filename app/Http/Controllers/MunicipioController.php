<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMunicipioRequest;
use App\Http\Requests\UpdateMunicipioRequest;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:municipios.viewAny')->only('index');
        $this->middleware('permission:municipios.view')->only('show');
        $this->middleware('permission:municipios.create')->only(['create','store']);
        $this->middleware('permission:municipios.update')->only(['edit','update']);
        $this->middleware('permission:municipios.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $perPage = (int)$request->get('per_page', 10);
        $perPage = in_array($perPage,[5,10,15,25,50]) ? $perPage : 10;

        $municipios = Municipio::query()
            ->when($q, fn($x)=>$x->where(function($y) use($q){
                $y->where('nombre','like',"%{$q}%")
                    ->orWhere('codigo_postal','like',"%{$q}%");
            }))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.municipios.index', compact('municipios','q','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.municipios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMunicipioRequest $request)
    {
        Municipio::create($request->validated());
        return redirect()->route('admin.municipios.index')->with('status','Municipio creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipio $municipio)
    {
        $municipio->loadCount('direcciones');
        return view('admin.municipios.show', compact('municipio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipio $municipio)
    {
        return view('admin.municipios.edit', compact('municipio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMunicipioRequest $request, Municipio $municipio)
    {
        $municipio->update($request->validated());
        return redirect()->route('admin.municipios.index')->with('status','Municipio actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipio $municipio)
    {
        $municipio->delete(); // soft delete
        return redirect()->route('admin.municipios.index')->with('status','Municipio eliminado');
    }
}
