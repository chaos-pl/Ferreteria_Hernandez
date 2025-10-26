@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $unidad->nombre }} ({{ $unidad->abreviatura }})</h3>
            <div class="d-flex gap-2">
                @can('unidades.update')
                    <a href="{{ route('admin.unidades.edit',$unidad) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.unidades.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-0"><strong>Productos asociados:</strong> {{ $unidad->productos_count ?? $unidad->productos()->count() }}</p>
            </div>
            <div class="card-footer small text-muted">
                ID: {{ $unidad->id }} · Creada: {{ $unidad->created_at }} · Actualizada: {{ $unidad->updated_at }}
            </div>
        </div>
    </div>
@endsection
