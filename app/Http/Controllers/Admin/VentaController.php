<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;
use App\Http\Requests\VentaRequest;
use App\Models\User;
use App\Models\VentaDetalle;
use App\Models\AsignaProductoProveedor;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ventas.viewAny')->only(['index']);
        $this->middleware('permission:ventas.create')->only(['create','store']);
        $this->middleware('permission:ventas.view')->only(['show']);
        $this->middleware('permission:ventas.update')->only(['edit','update']);
        $this->middleware('permission:ventas.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $q       = trim((string)$request->get('q',''));
        $perPage = (int)$request->get('per_page', 10);

        $query = Venta::with('user')
            ->when($q !== '', function($qq) use ($q) {
                $qq->where('id', (int)$q)
                    ->orWhere('estado','like', "%{$q}%")
                    ->orWhereHas('user', fn($u)=>$u->where('name','like',"%{$q}%"));
            })
            ->orderByDesc('fecha_venta');

        $ventas = $query->paginate($perPage)->withQueryString();

        return view('admin.ventas.index', compact('ventas','q','perPage'));
    }

    public function create()
    {
        $usuarios = User::orderBy('name')->get(['id','name']);
        $apps = \App\Models\AsignaProductoProveedor::with([
            'producto:id,nombre',
            'proveedor:id,personas_id',
            'proveedor.persona:id,nombre,ap,am',
        ])->orderBy('id','asc')->get();

        $venta = new Venta([
            'fecha_venta' => now(),
            'estado'      => 'carrito',
            'descuentos'  => 0,
            'impuestos'   => 0,
        ]);

        return view('admin.ventas.create', compact('venta','usuarios','apps'));
    }

    public function store(VentaRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $venta = Venta::create([
                'users_id'    => $request->users_id,
                'fecha_venta' => $request->fecha_venta,
                'estado'      => $request->estado,
                'descuentos'  => $request->input('descuentos',0),
                'impuestos'   => $request->input('impuestos',0),
                'subtotal'    => 0,
                'total'       => 0,
            ]);

            $appIDs  = (array) ($request->app_id      ?? []);
            $cantids = (array) ($request->cantidad    ?? []);
            $precios = (array) ($request->precio_unit ?? []);

            $lineas = $this->normalizarLineas($appIDs, $cantids, $precios);

            foreach ($lineas as $ln) {
                VentaDetalle::create([
                    'ventas_id'                       => $venta->id,
                    'asigna_productos_proveedores_id' => $ln['app_id'],
                    'cantidad'                        => $ln['cantidad'],
                    'precio_unit'                     => $ln['precio_unit'],
                    'subtotal'                        => $ln['cantidad'] * $ln['precio_unit'],
                ]);
            }

            $venta->recalcTotales();

            // ↓ Si se crea ya como pagada, descuenta existencias
            if ($venta->estado === 'pagada') {
                $this->aplicarStock($venta); // descuenta
            }

            return redirect()
                ->route('admin.ventas.show', $venta)
                ->with('status','Venta creada correctamente');
        });
    }

    public function show(Venta $venta)
    {
        $venta->load(['user','detalles.asignaProductoProveedor.producto','detalles.asignaProductoProveedor.proveedor']);
        return view('admin.ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $venta->load('detalles');
        $usuarios = User::orderBy('name')->get(['id','name']);
        $apps = \App\Models\AsignaProductoProveedor::with([
            'producto:id,nombre',
            'proveedor:id,personas_id',
            'proveedor.persona:id,nombre,ap,am',
        ])->orderBy('id','asc')->get();

        return view('admin.ventas.edit', compact('venta','usuarios','apps'));
    }

    public function update(VentaRequest $request, Venta $venta)
    {
        return DB::transaction(function() use ($request, $venta) {

            // Estado anterior (para detectar transición)
            $wasPagada = $venta->estado === 'pagada';

            // Guarda detalle anterior (para revertir stock si era pagada)
            $detallesAnteriores = $venta->detalles()
                ->with('asignaProductoProveedor:id,productos_id')
                ->get();

            // Actualiza encabezado
            $venta->update([
                'users_id'    => $request->users_id,
                'fecha_venta' => $request->fecha_venta,
                'estado'      => $request->estado,
                'descuentos'  => $request->input('descuentos',0),
                'impuestos'   => $request->input('impuestos',0),
            ]);

            // Si estaba pagada, primero REGRESA stock de los renglones anteriores
            if ($wasPagada) {
                $this->ajustarStockPorLineas($detallesAnteriores, +1);
            }

            // Reemplaza renglones
            $venta->detalles()->withTrashed()->forceDelete();

            $appIDs  = (array) ($request->app_id      ?? []);
            $cantids = (array) ($request->cantidad    ?? []);
            $precios = (array) ($request->precio_unit ?? []);

            $lineas = $this->normalizarLineas($appIDs, $cantids, $precios);

            foreach ($lineas as $ln) {
                VentaDetalle::create([
                    'ventas_id'                       => $venta->id,
                    'asigna_productos_proveedores_id' => $ln['app_id'],
                    'cantidad'                        => $ln['cantidad'],
                    'precio_unit'                     => $ln['precio_unit'],
                    'subtotal'                        => $ln['cantidad'] * $ln['precio_unit'],
                ]);
            }

            $venta->recalcTotales();

            // Si AHORA queda en pagada, aplica descuento de stock con los renglones nuevos
            if ($venta->estado === 'pagada') {
                $this->aplicarStock($venta); // descuenta
            }

            return redirect()
                ->route('admin.ventas.show', $venta)
                ->with('status','Venta actualizada');
        });
    }

    public function destroy(Venta $venta)
    {
        return DB::transaction(function() use ($venta) {
            // Si estaba pagada, regresar stock antes de borrar
            if ($venta->estado === 'pagada') {
                $this->ajustarStockPorLineas(
                    $venta->detalles()->with('asignaProductoProveedor:id,productos_id')->get(),
                    +1
                );
            }
            $venta->delete();
            return redirect()->route('admin.ventas.index')->with('status','Venta eliminada');
        });
    }
    private function aplicarStock(Venta $venta): void
    {
        $venta->loadMissing('detalles.asignaProductoProveedor');
        $this->ajustarStockPorLineas($venta->detalles, -1);
    }

    /**
     * Ajusta existencias por líneas: sign = -1 descuenta, +1 regresa.
     * Bloquea filas para concurrencia y evita negativos.
     */
    private function ajustarStockPorLineas($detalles, int $sign): void
    {
        foreach ($detalles as $d) {
            $app = $d->asignaProductoProveedor;              // tiene productos_id
            if (!$app) continue;

            // lock FOR UPDATE para evitar carreras
            $producto = Producto::whereKey($app->productos_id)->lockForUpdate()->first();

            if (!$producto) continue;

            $delta = $sign * (int)$d->cantidad;

            if ($sign < 0 && $producto->existencias < $d->cantidad) {
                throw ValidationException::withMessages([
                    'existencias' => "Stock insuficiente del producto '{$producto->nombre}'. Disponibles: {$producto->existencias}, solicitados: {$d->cantidad}."
                ]);
            }

            // aplica ajuste y guarda
            $producto->existencias = max(0, $producto->existencias + $delta);
            $producto->save();
        }
    }
    /**
     * Une líneas repetidas por app_id: suma cantidades y usa el último precio.
     * Retorna un arreglo plano con ['app_id','cantidad','precio_unit'].
     */
    private function normalizarLineas(array $appIDs, array $cantids, array $precios): array
    {
        $agg = [];
        foreach ($appIDs as $i => $id) {
            $appId    = (int) $id;
            if ($appId <= 0) continue;

            $cant     = (int) ($cantids[$i]  ?? 0);
            if ($cant <= 0)  continue;

            $precio   = (float) ($precios[$i] ?? 0);

            if (isset($agg[$appId])) {
                $agg[$appId]['cantidad']    += $cant;
                $agg[$appId]['precio_unit']  = $precio; // o calcula un promedio si lo prefieres
            } else {
                $agg[$appId] = [
                    'app_id'      => $appId,
                    'cantidad'    => $cant,
                    'precio_unit' => $precio,
                ];
            }
        }
        return array_values($agg);
    }

}
