<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Venta;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * LANDING PUBLICA
     */
    public function index()
    {

        $categorias = Categoria::withCount(['productos' => function ($q) {
            $q->where('estado', 'activo');
        }])
            ->whereHas('productos', fn($q) => $q->where('estado', 'activo'))
            ->take(6)
            ->get();

        $productos = Producto::where('estado', 'activo')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $imagenesCategorias = [
            'Fonantería y plomería'      => '/img/cat-fontaneria.jpeg',
            'Electricidad y iluminación' => '/img/cat-electricidad.jpeg',
            'Ferretería general'         => '/img/cat-ferreteria.jpeg',
            'Jardinería y exteriores'    => '/img/cat-jardineria.jpeg',
        ];

        return view('landing_page', compact('categorias', 'productos', 'imagenesCategorias'));
    }

    /**
     * DASHBOARD
     */
    public function dashboard()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('cliente')) {
                return redirect()->route('shop.catalogo');
            }
        }
        // Métricas
        $productosStockBajo = Producto::where('existencias', '<=', 5)->count();
        $totalProductos = Producto::count();
        $categorias = Categoria::count();
        $proveedores = Proveedor::count();
        $clientes = User::role('cliente')->count();
        $usuarios = User::count();

        // Ventas del mes — CORREGIDO → se usa created_at
        $ventasMes = Venta::whereMonth('created_at', now()->month)->count();
        $ingresosMes = Venta::whereMonth('created_at', now()->month)->sum('total');

        // Datos para gráficas
        $ventasPorMes = Venta::selectRaw("MONTH(created_at) as mes, COUNT(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $ingresosPorMes = Venta::selectRaw("MONTH(created_at) as mes, SUM(total) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        return view('home', compact(
            'productosStockBajo',
            'totalProductos',
            'categorias',
            'proveedores',
            'clientes',
            'usuarios',
            'ventasMes',
            'ingresosMes',
            'ventasPorMes',
            'ingresosPorMes'
        ));
    }
}
