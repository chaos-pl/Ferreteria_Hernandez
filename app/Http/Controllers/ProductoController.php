<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Unidad;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:productos.viewAny')->only('index');
        $this->middleware('permission:productos.view')->only('show');
        $this->middleware('permission:productos.create')->only(['create','store']);
        $this->middleware('permission:productos.update')->only(['edit','update']);
        $this->middleware('permission:productos.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $q        = trim((string)$request->get('q'));
        $cat      = $request->get('categoria');
        $uni      = $request->get('unidad');
        $mar      = $request->get('marca');
        $estado   = $request->get('estado');
        $perPage  = (int)$request->get('per_page', 10);
        $perPage  = in_array($perPage,[5,10,15,25,50]) ? $perPage : 10;

        $productos = Producto::query()
            ->with(['categoria','unidad','marca'])
            ->when($q, fn($x)=>$x->where(function($y) use($q){
                $y->where('nombre','like',"%{$q}%")
                    ->orWhere('descripcion','like',"%{$q}%");
            }))
            ->when($cat, fn($x)=>$x->where('categorias_id',$cat))
            ->when($uni, fn($x)=>$x->where('unidades_id',$uni))
            ->when($mar, fn($x)=>$x->where('marcas_id',$mar))
            ->when(in_array($estado,['activo','inactivo']), fn($x)=>$x->where('estado',$estado))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        $categorias = Categoria::orderBy('nombre')->get(['id','nombre']);
        $unidades   = Unidad::orderBy('nombre')->get(['id','nombre']);
        $marcas     = Marca::orderBy('nombre')->get(['id','nombre']);

        return view('admin.productos.index', compact(
            'productos','q','cat','uni','mar','estado','perPage','categorias','unidades','marcas'
        ));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get(['id','nombre']);
        $unidades   = Unidad::orderBy('nombre')->get(['id','nombre']);
        $marcas     = Marca::orderBy('nombre')->get(['id','nombre']);
        return view('admin.productos.create', compact('categorias','unidades','marcas'));
    }

    public function store(StoreProductoRequest $request)
    {
        $data = $request->validated();

        // El estado NO se manipula
        $data['estado'] = 'activo';

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen_url'] = Storage::url($path);
        }

        try {
            // Crear producto
            Producto::create($data);
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.productos.index')
            ->with('status','Producto creado correctamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria','unidad','marca']);
        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get(['id','nombre']);
        $unidades   = Unidad::orderBy('nombre')->get(['id','nombre']);
        $marcas     = Marca::orderBy('nombre')->get(['id','nombre']);
        return view('admin.productos.edit', compact('producto','categorias','unidades','marcas'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen_url'] = Storage::url($path);
        }

        // ESTADO AUTOMÁTICO SEGÚN EXISTENCIAS
        if ($data['existencias'] == 0) {
            $data['estado'] = 'inactivo';
        } else {
            $data['estado'] = 'activo';
        }

        try {
            $producto->update($data);
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.productos.index')
            ->with('status','Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        // Cambiar estado a inactivo
        $producto->estado = 'inactivo';
        $producto->save();

        // Soft delete (NO elimina de la BD)
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('status','Producto desactivado correctamente.');
    }

}
