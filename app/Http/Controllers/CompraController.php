<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\AsignaProductoProveedor;
use App\Http\Requests\StoreCompraRequest;
use App\Http\Requests\UpdateCompraRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
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

        return view('admin.compras.index', compact('list','q','perPage'))
            ->with('compras', $list);
    }

    public function create()
    {
        $proveedores = Proveedor::with('persona')->orderBy('id')->get();
        $asignaciones = AsignaProductoProveedor::with(['producto','proveedor.persona'])
            ->orderBy('id')->get();

        return view('admin.compras.create', compact('proveedores','asignaciones'));
    }

    public function store(StoreCompraRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {

            // 1. Crear compra
            $compra = Compra::create([
                'proveedores_id' => $data['proveedores_id'],
                'users_id'       => Auth::id(),
                'fecha_compra'   => now(),
                'estado'         => 'ordenada',
                'total'          => 0, // valor inicial
            ]);

            // 2. Crear detalles
            $asignas     = $data['asigna_ids'];
            $cantidades  = $data['cantidades'];
            $costos      = $data['costos'];

            for ($i = 0; $i < count($asignas); $i++) {

                $subtotal = $cantidades[$i] * $costos[$i];

                $compra->detalles()->create([
                    'asigna_productos_proveedores_id' => $asignas[$i],
                    'cantidad'                        => $cantidades[$i],
                    'costo_unit'                      => $costos[$i],
                    'subtotal'                        => $subtotal,
                ]);
            }

            // 3. Ahora sí calcular total
            $compra->recalcularTotal();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('admin.compras.index')
            ->with('status','Compra registrada correctamente.');
    }



    public function show(Compra $compra)
    {
        $compra->load(['proveedor.persona','usuario','detalles.asignacion.producto','detalles.asignacion.proveedor.persona']);
        return view('admin.compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        $compra->load('detalles.asignacion.producto','detalles.asignacion.proveedor.persona');
        return view('admin.compras.edit', compact('compra'));
    }

    public function update(UpdateCompraRequest $request, Compra $compra)
    {
        DB::beginTransaction();

        try {
            $prevEstado = $compra->estado;

            $compra->update([
                'estado' => $request->estado,
            ]);

            // Si cambia a recibida, se ingresan existencias
            if ($prevEstado !== 'recibida' && $request->estado === 'recibida') {

                foreach ($compra->detalles as $d) {

                    DB::table('productos')
                        ->where('id', function($q) use ($d){
                            $q->select('productos_id')
                                ->from('asigna_productos_proveedores')
                                ->where('id', $d->asigna_productos_proveedores_id);
                        })
                        ->increment('existencias', $d->cantidad);
                }
            }

            DB::commit();
            return redirect()->route('admin.compras.show',$compra)
                ->with('status','Compra actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Compra $compra)
    {
        $compra->delete();
        return redirect()->route('admin.compras.index')
            ->with('status','Compra eliminada correctamente.');
    }
}
