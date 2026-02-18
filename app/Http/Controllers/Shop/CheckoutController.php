<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Carrito, CarritoItem, Producto, AsignaProductoProveedor, Venta, VentaDetalle};
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'cliente']);
    }

    /**
     * Mostrar resumen del checkout
     */
    public function index()
    {
        $cart = Carrito::with(['items.producto'])
            ->abierto()
            ->where('users_id', auth()->id())
            ->firstOrFail();

        return view('shop.checkout', compact('cart'));
    }

    /**
     * Procesar la compra
     */
    public function process(Request $r)
    {
        $user = auth()->user();

        $cart = Carrito::with(['items'])
            ->abierto()
            ->where('users_id', $user->id)
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'El carrito está vacío.');
        }

        DB::transaction(function() use ($user, $cart) {

            $venta = Venta::create([
                'users_id'   => $user->id,
                'estado'     => 'pagada',
                'subtotal'   => 0,
                'descuentos' => 0,
                'impuestos'  => 0,
                'total'      => 0,
            ]);

            $subtotal = 0;

            foreach ($cart->items as $item) {

                // Buscar proveedor del producto
                $app = AsignaProductoProveedor::where('productos_id', $item->productos_id)->first();

                if (! $app) {
                    throw new \Exception("El producto {$item->producto->nombre} no tiene proveedor asignado.");
                }

                $lineTotal = $item->cantidad * $item->precio_unit;

                VentaDetalle::create([
                    'ventas_id'                        => $venta->id,
                    'asigna_productos_proveedores_id'  => $app->id,
                    'cantidad'                         => $item->cantidad,
                    'precio_unit'                      => $item->precio_unit,
                    'subtotal'                         => $lineTotal,
                ]);

                $subtotal += $lineTotal;
            }

            $venta->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal, // sin IVA por ahora
            ]);

            // cerrar carrito
            $cart->update(['estado' => 'cerrado']);
        });

        return redirect()
            ->route('shop.catalogo')
            ->with('status', 'Tu compra fue procesada exitosamente.');
    }
}
