<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:marcas.viewAny')->only('index');
        $this->middleware('permission:marcas.view')->only('show');
        $this->middleware('permission:marcas.create')->only(['create','store']);
        $this->middleware('permission:marcas.update')->only(['edit','update']);
        $this->middleware('permission:marcas.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $perPage = (int)$request->get('per_page', 10);
        $perPage = in_array($perPage,[5,10,15,25,50]) ? $perPage : 10;

        $marcas = Marca::query()
            ->when($q, fn($x)=>$x->where('nombre','like',"%{$q}%"))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.marcas.index', compact('marcas','q','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        Marca::create($request->validated());
        return redirect()->route('admin.marcas.index')->with('status','Marca creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        $marca->loadCount('productos');
        return view('admin.marcas.show', compact('marca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return view('admin.marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, Marca $marca)
    {
        $marca->update($request->validated());
        return redirect()->route('admin.marcas.index')->with('status','Marca actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        $marca->delete(); // soft delete
        return redirect()->route('admin.marcas.index')->with('status','Marca eliminada');
    }
}
