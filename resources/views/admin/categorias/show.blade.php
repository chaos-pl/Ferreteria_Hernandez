@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $categoria->nombre }}</h3>
            <div>
                @can('categorias.update')
                    <a href="{{ route('admin.categorias.edit',$categoria) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <p class="mb-2"><strong>Descripción:</strong></p>
                <p class="mb-0">{{ $categoria->descripcion ?: '—' }}</p>
            </div>
        </div>

        {{-- Filtro / búsqueda de productos de esta categoría --}}
        <form method="get" class="row g-2 align-items-end mb-3">
            <div class="col-md-6">
                <label class="form-label">Buscar producto</label>
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nombre o descripción">
            </div>
            <div class="col-md-3">
                <label class="form-label">Por página</label>
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([8,12,16,24,48] as $n)
                        <option value="{{ $n }}" {{ (int)$perPage===$n ? 'selected':'' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100">Aplicar</button>
            </div>
        </form>

        {{-- Grid de productos --}}
        @if($productos->count())
            <div class="row g-3">
                @foreach($productos as $p)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="ratio ratio-4x3 bg-light">
                                @if($p->imagen_url)
                                    <img src="{{ $p->imagen_url }}" class="w-100 h-100" style="object-fit:cover" alt="{{ $p->nombre }}">
                                @else
                                    <img src="{{ asset('img/placeholder-product.png') }}" class="w-100 h-100" style="object-fit:cover" alt="sin imagen">
                                @endif
                            </div>
                            <div class="card-body">
                                <h6 class="mb-1">
                                    @can('productos.view')
                                        <a class="text-decoration-none" href="{{ route('admin.productos.show',$p) }}">{{ $p->nombre }}</a>
                                    @else
                                        {{ $p->nombre }}
                                    @endcan
                                </h6>
                                <div class="text-muted small mb-1">
                                    {{ $p->marca->nombre ?? '—' }}
                                    @if($p->unidad) · {{ $p->unidad->abreviatura ?? $p->unidad->nombre }} @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">${{ number_format($p->precio,2) }}</span>
                                    <span class="badge {{ $p->existencias>0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ $p->existencias }} en stock
                                </span>
                                </div>
                            </div>
                            <div class="card-footer bg-white d-flex gap-2">
                                @can('productos.update')
                                    <a href="{{ route('admin.productos.edit',$p) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                @endcan
                                @can('productos.delete')
                                    <form action="{{ route('admin.productos.destroy',$p) }}" method="post" onsubmit="return confirm('¿Eliminar producto?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $productos->links() }}
            </div>
        @else
            <div class="alert alert-light border">No hay productos en esta categoría.</div>
        @endif
    </div>
@endsection
