<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Carrito, CarritoItem, Producto, AsignaProductoProveedor, Venta, VentaDetalle};
use Illuminate\Support\Facades\DB;
class CarritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    { $this->middleware('auth');
    }
    private function ensureCart($user)
    {
        return Carrito::firstOrCreate(['users_id'=>$user->id, 'estado'=>'abierto']);
    }

    public function index()
    {
        $user = auth()->user();

        // Crear carrito si no existe
        $cart = Carrito::with(['items.producto'])
            ->abierto()
            ->where('users_id', $user->id)
            ->first();

        if (!$cart) {
            $cart = Carrito::create([
                'users_id' => $user->id,
                'estado'   => 'abierto'
            ]);
            $cart->load('items.producto');
        }

        return view('shop.carrito', compact('cart'));
    }


    public function add(Request $r)
    {
        $r->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'nullable|integer|min:1'
        ]);

        $prod = Producto::findOrFail($r->producto_id);
        $qty  = max(1, (int)$r->cantidad);
        $cart = $this->ensureCart($r->user());

        // respeta el UNIQUE (carritos_id, productos_id)
        $item = CarritoItem::withTrashed()->where([
            'carritos_id'  => $cart->id,
            'productos_id' => $prod->id,
        ])->first();

        if ($item) {

            // si estaba eliminado → restaurarlo
            if ($item->trashed()) {
                $item->restore();
                $item->cantidad = $qty;
            } else {
                // si existe y NO está eliminado → sumar cantidad
                $item->cantidad += $qty;
            }

        } else {
            // si nunca existió → crearlo normalmente
            $item = CarritoItem::create([
                'carritos_id'  => $cart->id,
                'productos_id' => $prod->id,
                'cantidad'     => $qty,
                'precio_unit'  => $prod->precio,
            ]);
        }

        $item->precio_unit = $prod->precio;
        $item->save();


        return back()->with('status','Agregado al carrito');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $r, CarritoItem $item)
    {
        $r->validate(['cantidad'=>'required|integer|min:1']);
        $item->update(['cantidad'=>$r->cantidad]);
        return back();
    }


    public function remove(CarritoItem $item){
        $item->delete();
        return back();
    }

    public function clear(){
        $cart = $this->ensureCart(auth()->user());
        $cart->items()->delete();
        return back();
    }

    public function checkout(){
        $user = auth()->user();
        $cart = Carrito::with('items')->abierto()
            ->where('users_id',$user->id)->firstOrFail();

        if ($cart->items->isEmpty()) return back()->with('error','El carrito está vacío');

        DB::transaction(function() use ($user,$cart){
            $venta = Venta::create([
                'users_id'   => $user->id,
                'estado'     => 'pagada', // o 'carrito' si vas a integrar pago
                'subtotal'   => 0, 'descuentos'=>0, 'impuestos'=>0, 'total'=>0,
            ]);

            $subtotal = 0;
            foreach($cart->items as $it){
                // Tu detalle de venta requiere vínculo producto–proveedor (APP)
                $app = AsignaProductoProveedor::where('productos_id',$it->productos_id)->first();
                if(!$app) throw new \Exception('Producto sin proveedor asignado');

                $line = $it->cantidad * $it->precio_unit;

                VentaDetalle::create([
                    'ventas_id'                          => $venta->id,
                    'asigna_productos_proveedores_id'    => $app->id,
                    'cantidad'                           => $it->cantidad,
                    'precio_unit'                        => $it->precio_unit,
                    'subtotal'                           => $line,
                ]);

                $subtotal += $line;
            }

            $venta->update(['subtotal'=>$subtotal,'total'=>$subtotal]);
            $cart->update(['estado'=>'cerrado']);
        });

        return redirect()->route('shop.carrito')->with('status','Compra generada');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
