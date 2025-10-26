<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:categorias.viewAny')->only(['index']);
        $this->middleware('permission:categorias.view')->only(['show']);
        $this->middleware('permission:categorias.create')->only(['create','store']);
        $this->middleware('permission:categorias.update')->only(['edit','update']);
        $this->middleware('permission:categorias.delete')->only(['destroy']);
    }


    public function index(request $request)
    {
        //
        $q = $request->get('q');
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5,10,15,25,50]) ? $perPage : 10;

        $categorias = \App\Models\Categoria::query()
            ->when($q, fn($x)=>$x->where(function($y) use($q){
                $y->where('nombre','like',"%{$q}%")
                    ->orWhere('descripcion','like',"%{$q}%");
            }))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.categorias.index', compact('categorias','q','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        //
        Categoria::create($request->validated());
        return redirect()->route('admin.categorias.index')->with('status','Categoría creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
        return view('admin.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        //
        $categoria->update($request->validated());
        return redirect()->route('admin.categorias.index')->with('status','Categoría actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
        $categoria->delete(); // soft delete
        return redirect()->route('admin.categorias.index')->with('status','Categoría eliminada');
    }
}
