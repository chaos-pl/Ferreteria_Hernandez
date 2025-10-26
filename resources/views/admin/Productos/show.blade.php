@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $producto->nombre }}</h3>
            <div class="d-flex gap-2">
                @can('productos.update')
                    <a href="{{ route('admin.productos.edit',$producto) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        @if($producto->imagen_url)
                            <img src="{{ $producto->imagen_url }}" alt="" class="img-fluid rounded">
                        @else
                            <div class="text-muted">Sin imagen</div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <p class="mb-1"><strong>Categoría:</strong> {{ $producto->categoria?->nombre }}</p>
                        <p class="mb-1"><strong>Marca:</strong> {{ $producto->marca?->nombre ?? '—' }}</p>
                        <p class="mb-1"><strong>Unidad:</strong> {{ $producto->unidad?->nombre }}</p>
                        <p class="mb-1"><strong>Precio:</strong> ${{ number_format($producto->precio,2) }}</p>
                        <p class="mb-1"><strong>Existencias:</strong> {{ $producto->existencias }}</p>
                        <p class="mb-2"><strong>Estado:</strong>
                            <span class="badge text-bg-{{ $producto->estado==='activo'?'success':'secondary' }}">{{ ucfirst($producto->estado) }}</span>
                        </p>
                        <p class="mb-0"><strong>Descripción:</strong><br>{{ $producto->descripcion ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer small text-muted">
                ID: {{ $producto->id }} · Creado: {{ $producto->created_at }} · Actualizado: {{ $producto->updated_at }}
            </div>
        </div>
    </div>
@endsection
