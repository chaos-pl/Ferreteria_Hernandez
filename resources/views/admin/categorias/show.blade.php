
@extends('layouts.dashboard')
@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $categoria->nombre }}</h3>
            <div>
                @can('categorias.update')
                    <a href="{{ route('admin.categorias.edit',$categoria) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="mb-2"><strong>Descripción:</strong></p>
                <p class="mb-0">{{ $categoria->descripcion ?: '—' }}</p>
            </div>
        </div>
    </div>
@endsection
