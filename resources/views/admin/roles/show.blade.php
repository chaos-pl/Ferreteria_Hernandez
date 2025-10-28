@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Rol: {{ $role->name }}</h3>
            <div class="d-flex gap-2">
                @can('roles.update')
                    <a href="{{ route('admin.roles.edit',$role) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-2"><strong>Guard:</strong> {{ $role->guard_name }}</p>
                <p class="mb-3"><strong>Permisos:</strong></p>
                <div class="row g-2">
                    @forelse($role->permissions as $p)
                        <div class="col-sm-6 col-md-4">
                            <span class="badge bg-light text-dark border">{{ $p->name }}</span>
                        </div>
                    @empty
                        <p class="text-muted">Sin permisos asignados.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
