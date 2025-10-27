@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Asignación #{{ $asignacion->id }}</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.asignaciones.edit',$asignacion) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-2"><strong>Producto:</strong> {{ $asignacion->producto?->nombre }}</p>
                <p class="mb-2"><strong>Proveedor:</strong>
                    #{{ $asignacion->proveedor?->id }} —
                    {{ $asignacion->proveedor?->persona?->nombre }} {{ $asignacion->proveedor?->persona?->ap }} {{ $asignacion->proveedor?->persona?->am }}
                </p>
                <p class="mb-2"><strong>SKU proveedor:</strong> {{ $asignacion->sku_proveedor ?: '—' }}</p>
                <p class="mb-0"><strong>Plazo de entrega:</strong> {{ $asignacion->plazo_entrega ?: '—' }} días</p>
            </div>
            <div class="card-footer small text-muted">
                Creado: {{ $asignacion->created_at }} · Actualizado: {{ $asignacion->updated_at }}
            </div>
        </div>
    </div>
@endsection
