@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $marca->nombre }}</h3>
            <div class="d-flex gap-2">
                @can('marcas.update')
                    <a href="{{ route('admin.marcas.edit',$marca) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.marcas.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-0"><strong>Productos asociados:</strong> {{ $marca->productos_count ?? $marca->productos()->count() }}</p>
            </div>
            <div class="card-footer small text-muted">
                ID: {{ $marca->id }} · Creada: {{ $marca->created_at }} · Actualizada: {{ $marca->updated_at }}
            </div>
        </div>
    </div>
@endsection
