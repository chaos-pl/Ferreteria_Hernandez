@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Dirección #{{ $direccion->id }}</h3>
            <div class="d-flex gap-2">
                @can('direcciones.update')
                    <a href="{{ route('admin.direcciones.edit',$direccion) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.direcciones.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-2"><strong>Calle:</strong> {{ $direccion->calle }}</p>
                <p class="mb-2"><strong>Colonia:</strong> {{ $direccion->colonia ?? '—' }}</p>
                <p class="mb-0"><strong>Municipio:</strong> {{ $direccion->municipio->nombre ?? '—' }}</p>
            </div>
            <div class="card-footer small text-muted">
                Creada: {{ $direccion->created_at }} · Actualizada: {{ $direccion->updated_at }}
            </div>
        </div>
    </div>
@endsection
