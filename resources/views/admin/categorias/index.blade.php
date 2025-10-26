@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Categorías</h3>
            @can('categorias.create')
                <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">Nueva</a>
            @endcan
        </div>

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre o descripción">
            </div>
            <div class="col-md-3">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([5,10,15,25,50] as $n)
                        <option value="{{ $n }}" {{ (int)$perPage===$n ? 'selected':'' }}>{{ $n }} por página</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100">Filtrar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categorias as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td><a class="text-decoration-none" href="{{ route('admin.categorias.show',$c) }}">{{ $c->nombre }}</a></td>
                        <td class="text-muted">{{ \Illuminate\Support\Str::limit($c->descripcion, 60) }}</td>
                        <td class="text-end">
                            @can('categorias.update')
                                <a href="{{ route('admin.categorias.edit',$c) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('categorias.delete')
                                <form action="{{ route('admin.categorias.destroy',$c) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar categoría?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Sin categorías</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $categorias->links() }}
        </div>
    </div>
@endsection
