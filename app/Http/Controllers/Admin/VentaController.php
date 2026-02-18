<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\AsignaProductoProveedor;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ventas.viewAny')->only(['index']);
        $this->middleware('permission:ventas.create')->only(['create', 'store']);
        $this->middleware('permission:ventas.view')->only(['show']);
        $this->middleware('permission:ventas.update')->only(['edit', 'update']);
        $this->middleware('permission:ventas.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $perPage = (int)$request->get('per_page', 10);

        $query = Venta::with('user')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('id', (int)$q)
                    ->orWhere('estado','like', "%{$q}%")
                    ->orWhereHas('user', fn($u)=>$u->where('name','like',"%{$q}%"));
            })
            ->orderByDesc('created_at');

        $ventas = $query->paginate($perPage)->withQueryString();

        return view('admin.ventas.index', compact('ventas','q','perPage'));
    }

    public function create()
    {
        $usuarios = User::orderBy('name')->get(['id','name']);

        $apps = AsignaProductoProveedor::with([
            'producto' => fn($q) => $q->where('estado', 'activo'),
            'proveedor.persona'
        ])
            ->whereHas('producto', fn($q)=>$q->where('estado','activo'))
            ->get();


        $venta = new Venta([
            'estado' => 'pagada'
        ]);

        return view('admin.ventas.create', compact('usuarios','apps','venta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'app_id' => 'required|array',
            'cantidad' => 'required|array'
        ]);

        DB::beginTransaction();

        try {

            // 1. Crear venta
            $venta = Venta::create([
                'users_id'   => $request->users_id,
                'estado'     => 'pagada',
                'subtotal'   => 0,
                'descuentos' => 0,
                'impuestos'  => 0,
                'total'      => 0
            ]);

            // 2. Crear detalles sin precios (procedure los actualiza)
            foreach ($request->app_id as $i => $appId) {
                $venta->detalles()->create([
                    'asigna_productos_proveedores_id' => $appId,
                    'cantidad' => (int)$request->cantidad[$i],
                    'precio_unit' => 0,
                    'subtotal' => 0
                ]);
            }

            // 3. Llamar el procedure
            DB::statement("CALL procesar_venta($venta->id)");

            DB::commit();

            return redirect()
                ->route('admin.ventas.show', $venta)
                ->with('status', 'Venta registrada correctamente');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }


    public function show(Venta $venta)
    {
        $venta->load([
            'user',
            'detalles.asignaProductoProveedor.producto',
            'detalles.asignaProductoProveedor.proveedor.persona'
        ]);

        return view('admin.ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $usuarios = User::orderBy('name')->get(['id','name']);

        // No permitir editar ventas canceladas
        if ($venta->estado === 'cancelada') {
            return back()->withErrors(['error' => 'No se puede editar una venta cancelada.']);
        }

        return view('admin.ventas.edit', compact('venta','usuarios'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'estado'   => 'required|in:pagada,cancelada'
        ]);

        if ($venta->estado === 'cancelada') {
            return back()->withErrors(['error' => 'No se puede modificar una venta que ya fue cancelada.']);
        }

        // 2. Si el estado cambia a cancelada, llamar el PROCEDURE
        if ($request->estado === 'cancelada' && $venta->estado === 'pagada') {

            DB::beginTransaction();

            // Llamar procedure cancelar_venta
            DB::statement("CALL cancelar_venta($venta->id)");

            DB::commit();

            return redirect()->route('admin.ventas.show', $venta)
                ->with('status', 'Venta cancelada correctamente. Se regresaron las existencias.');
        }

        // 3. Si solo está cambiando cliente o dejando pagada
        $venta->update([
            'users_id' => $request->users_id,
            'estado'   => $request->estado
        ]);

        return redirect()->route('admin.ventas.show', $venta)
            ->with('status', 'Venta actualizada correctamente.');
    }


    public function destroy(Venta $venta)
    {
        // Si se elimina, es mejor cancelarla antes
        $venta->delete();

        return redirect()->route('admin.ventas.index')
            ->with('status','Venta eliminada');
    }
}
