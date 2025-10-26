@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $municipio->nombre }}</h3>
            <div class="d-flex gap-2">
                @can('municipios.update')
                    <a href="{{ route('admin.municipios.edit',$municipio) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.municipios.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-2"><strong>Código postal:</strong> {{ $municipio->codigo_postal ?? '—' }}</p>
                <p class="mb-0"><strong>Direcciones registradas:</strong> {{ $municipio->direcciones_count ?? $municipio->direcciones()->count() }}</p>
            </div>
            <div class="card-footer small text-muted">
                ID: {{ $municipio->id }} · Creado: {{ $municipio->created_at }} · Actualizado: {{ $municipio->updated_at }}
            </div>
        </div>
    </div>
@endsection
