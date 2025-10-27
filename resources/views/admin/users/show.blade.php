@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Usuario #{{ $user->id }}</h3>
            <div class="d-flex gap-2">
                @can('users.update')
                    <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-1"><strong>Nombre:</strong> {{ $user->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-1"><strong>Estado:</strong>
                    <span class="badge text-bg-{{ $user->estado === 'activo' ? 'success' : 'secondary' }}">{{ $user->estado }}</span>
                </p>
                <p class="mb-1"><strong>Persona:</strong> {{ $user->persona->nombre ?? '—' }}</p>
                <p class="mb-0"><strong>Roles:</strong> {{ $user->roles->pluck('name')->join(', ') ?: '—' }}</p>
            </div>
            <div class="card-footer small text-muted">
                Creado: {{ $user->created_at }} · Actualizado: {{ $user->updated_at }}
            </div>
        </div>
    </div>
@endsection
