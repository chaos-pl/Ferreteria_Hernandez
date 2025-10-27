<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use App\Models\CompraDetalle;
use App\Models\Proveedor;
use App\Models\AsignaProductoProveedor;
use App\Http\Requests\StoreCompraRequest;
use App\Http\Requests\UpdateCompraRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:compras.viewAny')->only('index');
        $this->middleware('permission:compras.view')->only('show');
        $this->middleware('permission:compras.create')->only(['create','store']);
        $this->middleware('permission:compras.update')->only(['edit','update']);
        $this->middleware('permission:compras.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q       = trim((string) $request->get('q',''));
        $perPage = (int) $request->get('per_page', 10);

        $list = Compra::with(['proveedor.persona','usuario'])
            ->when($q !== '', function($query) use ($q){
                $query->where('id', $q)
                    ->orWhereHas('proveedor.persona', function($qq) use ($q){
                        $qq->where('nombre','like',"%$q%");
                    });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.compras.index', [
            'compras'  => $list,
            'q'        => $q,
            'perPage'  => $perPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::with('persona')->orderBy('id')->get();
        // Ojo: en una versión más avanzada, filtra asignaciones por proveedor seleccionado (AJAX).
        $asignaciones = AsignaProductoProveedor::with(['producto','proveedor.persona'])
            ->orderBy('id')->get();

        return view('admin.compras.create', compact('proveedores','asignaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function() use ($data) {
            $compra = Compra::create([
                'proveedores_id' => $data['proveedores_id'],
                'users_id'       => Auth::id(),
                'fecha_compra'   => $data['fecha_compra'],
                'estado'         => $data['estado'],
                'total'          => 0,
            ]);

            $total = 0;
            foreach ($data['asigna_ids'] as $i => $asignaId) {
                $cant  = (int) $data['cantidades'][$i];
                $costo = (float) $data['costos'][$i];
                $sub   = $cant * $costo;

                CompraDetalle::create([
                    'compras_id'                         => $compra->id,
                    'asigna_productos_proveedores_id'    => $asignaId,
                    'cantidad'                           => $cant,
                    'costo_unit'                         => $costo,
                    'subtotal'                           => $sub,
                ]);

                $total += $sub;
            }

            $compra->update(['total' => $total]);
        });

        return redirect()->route('admin.compras.index')
            ->with('status','Compra registrada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load(['proveedor.persona','usuario','detalles.asignacion.producto','detalles.asignacion.proveedor.persona']);
        return view('admin.compras.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        $compra->load('detalles.asignacion.producto','detalles.asignacion.proveedor.persona');

        $proveedores = Proveedor::with('persona')->orderBy('id')->get();
        $asignaciones = AsignaProductoProveedor::with(['producto','proveedor.persona'])
            ->orderBy('id')->get();

        return view('admin.compras.edit', compact('compra','proveedores','asignaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompraRequest $request, Compra $compra)
    {
        $data = $request->validated();

        DB::transaction(function() use ($compra, $data) {
            $compra->update([
                'proveedores_id' => $data['proveedores_id'],
                'fecha_compra'   => $data['fecha_compra'],
                'estado'         => $data['estado'],
            ]);

            // Reemplazamos detalles
            $compra->detalles()->delete();

            $total = 0;
            foreach ($data['asigna_ids'] as $i => $asignaId) {
                $cant  = (int) $data['cantidades'][$i];
                $costo = (float) $data['costos'][$i];
                $sub   = $cant * $costo;

                CompraDetalle::create([
                    'compras_id'                         => $compra->id,
                    'asigna_productos_proveedores_id'    => $asignaId,
                    'cantidad'                           => $cant,
                    'costo_unit'                         => $costo,
                    'subtotal'                           => $sub,
                ]);

                $total += $sub;
            }
            $compra->update(['total' => $total]);
        });

        return redirect()->route('admin.compras.show', $compra)
            ->with('status','Compra actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        $compra->delete();
        return redirect()->route('admin.compras.index')
            ->with('status','Compra eliminada.');
    }
}
