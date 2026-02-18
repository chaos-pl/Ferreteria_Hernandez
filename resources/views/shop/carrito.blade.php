@extends('layouts.app')

@section('content')

    <style>
        /* ===== Estilos tipo Mercado Libre ===== */
        .cart-item-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 16px;
            transition: 0.2s ease;
        }
        .cart-item-card:hover {
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        }
        .product-img {
            width: 90px;
            height: 90px;
            border-radius: 8px;
            object-fit: cover;
            background: #f5f5f5;
        }
        .btn-ml-primary {
            background: #3483fa;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 16px;
        }
        .btn-ml-primary:hover {
            background: #2968c8;
            color: white;
        }
        .btn-ml-danger {
            background: #ff4d4d;
            border-radius: 8px;
            color: white;
            padding: 8px 14px;
        }
        .btn-ml-danger:hover {
            background: #e60000;
            color: white;
        }
        .quantity-input {
            width: 70px;
            border-radius: 6px;
        }
    </style>

    <div class="container py-4">

        <h2 class="fw-bold mb-4">Mi carrito</h2>

        @if(!$cart || $cart->items->isEmpty())
            <div class="alert alert-info">Tu carrito está vacío.</div>
            <a href="{{ route('shop.catalogo') }}" class="btn btn-ml-primary">Ir al catálogo</a>
            @return
        @endif

        <div class="row g-3">

            @foreach($cart->items as $item)

                <div class="col-12">

                    <div class="cart-item-card d-flex align-items-center gap-3">

                        {{-- Imagen --}}
                        <img src="{{ $item->producto->imagen_url ?? asset('img/noimg.jpg') }}"
                             class="product-img" alt="Producto">

                        <div class="flex-grow-1">

                            {{-- Nombre --}}
                            <h5 class="mb-1">{{ $item->producto->nombre }}</h5>

                            {{-- Botón Ver más (abre modal) --}}
                            <button class="btn btn-link p-0" data-bs-toggle="modal"
                                    data-bs-target="#modalProducto{{ $item->id }}">
                                Ver más detalles
                            </button>

                            {{-- Precio --}}
                            <div class="fw-bold mt-2 text-success">
                                ${{ number_format($item->precio_unit,2) }} c/u
                            </div>
                        </div>

                        {{-- Cantidad --}}
                        <div class="text-center">
                            <form method="POST" action="{{ route('shop.carrito.update', $item) }}">
                                @csrf @method('PATCH')

                                <input type="number" name="cantidad"
                                       class="form-control quantity-input mb-2"
                                       min="1" value="{{ $item->cantidad }}">

                                <button class="btn btn-sm btn-outline-secondary">
                                    Actualizar
                                </button>
                            </form>
                        </div>

                        {{-- Subtotal --}}
                        <div class="fw-bold fs-5 text-end" style="width:140px;">
                            ${{ number_format($item->cantidad * $item->precio_unit, 2) }}
                        </div>


                        {{-- Eliminar --}}
                        <form method="POST" action="{{ route('shop.carrito.remove', $item) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-ml-danger">
                                Quitar
                            </button>
                        </form>

                    </div>
                </div>

                {{-- MODAL DEL PRODUCTO --}}
                <div class="modal fade" id="modalProducto{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">{{ $item->producto->nombre }}</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3">

                                    <div class="col-md-5">
                                        <img src="{{ $item->producto->imagen_url ?? asset('img/noimg.jpg') }}"
                                             class="img-fluid rounded shadow-sm">
                                    </div>

                                    <div class="col-md-7">
                                        <h4 class="text-success fw-bold">
                                            ${{ number_format($item->precio_unit,2) }}
                                        </h4>

                                        <p class="mt-3">
                                            {{ $item->producto->descripcion ?? 'Sin descripción disponible.' }}
                                        </p>

                                        <p class="text-muted small">
                                            ID Producto: {{ $item->producto->id }}
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>

            @endforeach

        </div>

        <hr>

        <div class="text-end fs-4 my-3">
            <strong>Total:</strong>
            ${{ number_format($cart->subtotal, 2) }}
        </div>

        <div class="d-flex justify-content-end gap-3">

            <a href="{{ route('shop.catalogo') }}" class="btn btn-outline-primary">
                Seguir comprando
            </a>

            <form method="POST" action="{{ route('shop.carrito.clear') }}">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger">Vaciar carrito</button>
            </form>

            <form method="POST" action="{{ route('shop.carrito.checkout') }}">
                @csrf
                <button class="btn btn-ml-primary">Finalizar compra</button>
            </form>

        </div>

    </div>

@endsection
