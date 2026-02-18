@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
    <style>
        .thumb {
            height: 220px;
            background-size: cover;
            background-position: center;
            border-radius: 12px 12px 0 0;
        }
        .card-modern {
            border: 0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: .25s;
        }
        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0,0,0,0.1);
        }
        .filtros-box {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.06);
        }
    </style>

    <div class="container py-4">

        <h2 class="fw-bold mb-3">Catálogo de Productos</h2>

        <!-- ==================== FILTROS ==================== -->
        <div class="filtros-box mb-4">

            <form method="GET" action="{{ route('shop.catalogo') }}">

                <div class="row g-3">

                    <!-- BUSQUEDA -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Buscar producto</label>
                        <input type="text" name="q" value="{{ $q }}"
                               class="form-control"
                               placeholder="Ej: martillo, cable, broca…">
                    </div>

                    <!-- CATEGORIA -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Categoría</label>
                        <select name="categoria" class="form-select">
                            <option value="">Todas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}"
                                    @selected($categoria == $cat->id)>
                                    {{ $cat->nombre }} ({{ $cat->productos_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ORDEN -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Ordenar por</label>
                        <select name="orden" class="form-select">
                            <option value="recientes" @selected($orden=='recientes')>Más recientes</option>
                            <option value="precio_asc" @selected($orden=='precio_asc')>Precio: menor a mayor</option>
                            <option value="precio_desc" @selected($orden=='precio_desc')>Precio: mayor a menor</option>
                        </select>
                    </div>

                    <!-- BOTÓN -->
                    <div class="col-12 text-end">
                        <button class="btn btn-primary px-4"
                                style="background:var(--orange-700); border:0;">
                            Aplicar filtros
                        </button>
                    </div>

                </div>
            </form>

        </div>

        <!-- ==================== PRODUCTOS ==================== -->
        @if($productos->count()==0)
            <div class="alert alert-warning">
                No se encontraron productos con los filtros seleccionados.
            </div>
        @endif

        <div class="row g-4">
            @foreach($productos as $p)
                <div class="col-sm-6 col-lg-3">
                    <div class="card-modern h-100">

                        <div class="thumb"
                             style="background-image:url('{{ $p->imagen_url ?? '/img/noimg.jpg' }}');">
                        </div>

                        <div class="p-3">

                            <h6 class="text-muted mb-1">
                                {{ $p->marca->nombre ?? 'Sin marca' }}
                            </h6>

                            <h5 class="fw-bold">{{ $p->nombre }}</h5>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">$ {{ number_format($p->precio,2) }}</span>

                                <a class="text-orange fw-semibold"
                                   data-bs-toggle="modal"
                                   data-bs-target="#modalProducto{{ $p->id }}">
                                    Ver más
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ==================== MODAL ==================== -->
                <div class="modal fade" id="modalProducto{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">{{ $p->nombre }}</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3">

                                    <div class="col-md-5">
                                        <img src="{{ $p->imagen_url ?? '/img/noimg.jpg' }}"
                                             class="img-fluid rounded shadow-sm">
                                    </div>

                                    <div class="col-md-7">

                                        <h4 class="text-success fw-bold">
                                            $ {{ number_format($p->precio,2) }}
                                        </h4>

                                        <p class="mt-3">{{ $p->descripcion }}</p>

                                        <p class="text-muted small">Categoría: {{ $p->categoria->nombre ?? 'N/A' }}</p>
                                        <p class="text-muted small">Marca: {{ $p->marca->nombre ?? 'N/A' }}</p>
                                        <p class="text-muted small">Unidad: {{ $p->unidad->nombre ?? 'N/A' }}</p>

                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">

                                <form method="POST" action="{{ route('shop.carrito.add') }}">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $p->id }}">
                                    <button class="btn btn-primary"
                                            style="background:var(--orange-700); border:0;">
                                        Agregar al carrito
                                    </button>
                                </form>

                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cerrar
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            @endforeach
        </div>

        <!-- ==================== PAGINACIÓN ==================== -->
        <div class="mt-4">
            {{ $productos->links() }}
        </div>

    </div>
@endsection
