@extends('layouts.app')

@section('content')

    <style>
        .cat-title {
            font-family: var(--font-display);
            font-size: 1.8rem;
            margin-bottom: .7rem;
            color: var(--orange-700);
        }
        .product-card {
            border-radius: 16px;
            overflow: hidden;
            transition: all .25s ease;
            border: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            background: #fff;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 26px rgba(0,0,0,.15);
        }
        .product-card img {
            height: 170px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        .price-tag {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--orange-700);
        }
        .btn-cart {
            background: var(--orange-700);
            color: #fff;
            border-radius: 10px;
        }
        .btn-cart:hover {
            background: var(--orange-600);
        }
    </style>


    <div class="container py-4">

        <h2 class="mb-4">Catálogo de Productos</h2>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- ============================================================
             MODO BÚSQUEDA → MUESTRA SOLO RESULTADOS
        ================================================================= --}}
        @isset($productos)
            @if($productos->count() == 0)
                <div class="text-center text-muted py-5">
                    No se encontraron resultados para: <strong>{{ $q }}</strong>
                </div>
            @else
                <h4 class="mb-3">Resultados para: "{{ $q }}"</h4>

                <div class="row g-4">
                    @foreach($productos as $p)
                        <div class="col-md-3">
                            <div class="product-card card">

                                <img src="{{ $p->imagen_url ?? asset('img/noimg.png') }}">

                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold">{{ $p->nombre }}</h5>
                                    <p class="text-muted small">{{ Str::limit($p->descripcion, 60) }}</p>

                                    <div class="price-tag mb-2">${{ number_format($p->precio,2) }}</div>

                                    <button class="btn btn-link text-orange p-0 mb-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalProd{{ $p->id }}">
                                        Ver más
                                    </button>

                                    @if($p->existencias > 0)
                                        <form method="POST" action="{{ route('shop.carrito.add') }}" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $p->id }}">
                                            <input type="hidden" name="cantidad" value="1">
                                            <button class="btn btn-cart w-100">Agregar al carrito</button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>Sin existencias</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ========== Modal de vista rápida ========== --}}
                        <div class="modal fade" id="modalProd{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content p-3 rounded-4">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-bold">{{ $p->nombre }}</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ $p->imagen_url ?? asset('img/noimg.png') }}"
                                             class="w-100 mb-3 rounded shadow-sm" style="max-height:320px; object-fit:cover;">

                                        <p class="text-muted">{{ $p->descripcion }}</p>

                                        <div class="price-tag mb-3">${{ number_format($p->precio,2) }}</div>

                                        @if($p->existencias > 0)
                                            <form method="POST" action="{{ route('shop.carrito.add') }}">
                                                @csrf
                                                <input type="hidden" name="producto_id" value="{{ $p->id }}">
                                                <button class="btn btn-cart px-4">Agregar al carrito</button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">Sin existencias</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="mt-4">{{ $productos->links() }}</div>
            @endif
        @endisset


        {{-- ============================================================
             MODO CATÁLOGO COMPLETO → AGRUPADO POR CATEGORÍAS
        ================================================================= --}}
        @isset($categorias)
            @foreach($categorias as $cat)
                @if($cat->productos->count() > 0)

                    <h3 class="cat-title mt-4">{{ $cat->nombre }}</h3>
                    <div class="row g-4">

                        @foreach($cat->productos as $p)
                            <div class="col-md-3">
                                <div class="product-card card">

                                    <img src="{{ $p->imagen_url ?? asset('img/noimg.png') }}">

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="fw-bold">{{ $p->nombre }}</h5>
                                        <p class="text-muted small">{{ Str::limit($p->descripcion, 60) }}</p>

                                        <div class="price-tag mb-2">${{ number_format($p->precio,2) }}</div>

                                        <button class="btn btn-link text-orange p-0 mb-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalProd{{ $p->id }}">
                                            Ver más
                                        </button>

                                        @if($p->existencias > 0)
                                            <form method="POST" action="{{ route('shop.carrito.add') }}" class="mt-auto">
                                                @csrf
                                                <input type="hidden" name="producto_id" value="{{ $p->id }}">
                                                <input type="hidden" name="cantidad" value="1">
                                                <button class="btn btn-cart w-100">Agregar al carrito</button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>Sin existencias</button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Reutilizamos el modal generado arriba --}}
                            @include('shop.partials.modal-producto', ['p' => $p])

                        @endforeach

                    </div>
                @endif
            @endforeach
        @endisset

    </div>

@endsection
