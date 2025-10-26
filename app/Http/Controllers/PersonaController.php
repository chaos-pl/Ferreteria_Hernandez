<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Direccion;
use Illuminate\Http\Request;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:personas.viewAny')->only('index');
        $this->middleware('permission:personas.view')->only('show');
        $this->middleware('permission:personas.create')->only(['create','store']);
        $this->middleware('permission:personas.update')->only(['edit','update']);
        $this->middleware('permission:personas.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5,10,15,25,50]) ? $perPage : 10;

        $personas = Persona::query()
            ->with('direccion')
            ->when($q, fn($x)=>$x->where(function($y) use($q){
                $y->where('nombre','like',"%{$q}%")
                    ->orWhere('ap','like',"%{$q}%")
                    ->orWhere('am','like',"%{$q}%")
                    ->orWhere('telefono','like',"%{$q}%");
            }))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.personas.index', compact('personas','q','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $direcciones = Direccion::orderBy('id','desc')->limit(200)->get(['id','calle','colonia']);
        return view('admin.personas.create', compact('direcciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        Persona::create($request->validated());
        return redirect()->route('admin.personas.index')->with('status','Persona creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        $persona->load('direccion');
        return view('admin.personas.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        $direcciones = Direccion::orderBy('id','desc')->limit(200)->get(['id','calle','colonia']);
        return view('admin.personas.edit', compact('persona','direcciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        $persona->update($request->validated());
        return redirect()->route('admin.personas.index')->with('status','Persona actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        $persona->delete(); // soft delete
        return redirect()->route('admin.personas.index')->with('status','Persona eliminada');
    }
}
