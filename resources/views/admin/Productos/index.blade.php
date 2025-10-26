<?php
@extends('dashboard')
<div class="col-auto">
    <button class="btn btn-outline-secondary">Buscar</button>
</div>
</form>


<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th class="text-end">Precio</th>
            <th class="text-end">Existencias</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse($productos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>
                    <a href="{{ route('admin.productos.show', $p) }}" class="text-decoration-none">{{ $p->nombre }}</a>
                </td>
                <td class="text-end">${{ number_format($p->precio,2) }}</td>
                <td class="text-end">{{ $p->existencias }}</td>
                <td>
                    <span class="badge bg-{{ $p->estado==='activo' ? 'success':'secondary' }}">{{ ucfirst($p->estado) }}</span>
                </td>
                <td class="text-end">
                    @can('productos.update')
                        <a href="{{ route('admin.productos.edit', $p) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    @endcan
                    @can('productos.delete')
                        <form action="{{ route('admin.productos.destroy', $p) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar producto?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Sin productos</td></tr>
        @endforelse
        </tbody>
    </table>
</div>


{{ $productos->links() }}
</div>
@endsection
