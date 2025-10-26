@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $persona->nombre_completo }}</h3>
            <div class="d-flex gap-2">
                @can('personas.update')
                    <a href="{{ route('admin.personas.edit',$persona) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.personas.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4"><strong>Teléfono:</strong> {{ $persona->telefono ?? '—' }}</div>
                    <div class="col-12">
                        <strong>Dirección:</strong>
                        {{ $persona->direccion ? ($persona->direccion->calle.' · '.$persona->direccion->colonia) : '—' }}
                    </div>
                </div>
            </div>
            <div class="card-footer small text-muted">
                ID: {{ $persona->id }} · Creado: {{ $persona->created_at }} · Actualizado: {{ $persona->updated_at }}
            </div>
        </div>
    </div>
@endsection
