<?php
@extends('dashboard')
@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ $producto->nombre }}</h3>
            <div>
                @can('productos.update')
                    <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>


        <div class="row g-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2"><strong>Descripción:</strong></p>
                        <p class="mb-0">{{ $producto->descripcion ?: '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between"><span>Precio</span><strong>${{ number_format($producto->precio,2) }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Existencias</span><strong>{{ $producto->existencias }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Estado</span><span class="badge bg-{{ $producto->estado==='activo' ? 'success':'secondary' }}">{{ ucfirst($producto->estado) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span>ID</span><span>{{ $producto->id }}</span></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
