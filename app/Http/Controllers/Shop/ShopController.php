<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ShopController extends Controller
{
    public function catalogo(Request $request)
    {
        // Parámetros GET
        $q          = $request->get('q');                // búsqueda
        $categoria  = $request->get('categoria');        // filtro categoría
        $orden      = $request->get('orden', 'recientes'); // ordenamiento

        // Categorías activas
        $categorias = Categoria::withCount('productos')
            ->orderBy('nombre')
            ->get();

        // Query base de productos
        $productos = Producto::where('estado', 'activo');

        // ==========================
        // FILTRO: BÚSQUEDA
        // ==========================
        if ($q) {
            $productos->where('nombre', 'like', "%$q%");
        }

        // ==========================
        // FILTRO: CATEGORÍA
        // ==========================
        if ($categoria) {
            $productos->where('categorias_id', $categoria);
        }

        // ==========================
        // ORDENAMIENTO
        // ==========================
        switch ($orden) {
            case 'precio_asc':
                $productos->orderBy('precio', 'asc');
                break;

            case 'precio_desc':
                $productos->orderBy('precio', 'desc');
                break;

            default:
                $productos->orderBy('created_at', 'desc');
        }

        // Paginación final
        $productos = $productos->paginate(12)->appends($request->query());

        return view('shop.catalogo_pro', compact(
            'productos', 'categorias', 'q', 'categoria', 'orden'
        ));
    }

}
