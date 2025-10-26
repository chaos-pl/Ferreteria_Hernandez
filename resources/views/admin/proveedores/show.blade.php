@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Proveedor #{{ $proveedor->id }}</h3>
            <div class="d-flex gap-2">
                @can('proveedores.update')
                    <a href="{{ route('admin.proveedores.edit',$proveedor) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.proveedores.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-2"><strong>Persona:</strong>
                    {{ $proveedor->persona?->nombre }} {{ $proveedor->persona?->ap }} {{ $proveedor->persona?->am }}
                </p>
                <p class="mb-2"><strong>Teléfono:</strong> {{ $proveedor->persona?->telefono ?? '—' }}</p>
                <p class="mb-0"><strong>Estado:</strong>
                    <span class="badge text-bg-{{ $proveedor->estado==='activo'?'success':'secondary' }}">
          {{ ucfirst($proveedor->estado) }}
        </span>
                </p>
            </div>
            <div class="card-footer small text-muted">
                Creado: {{ $proveedor->created_at }} · Actualizado: {{ $proveedor->updated_at }}
            </div>
        </div>
    </div>
@endsection
