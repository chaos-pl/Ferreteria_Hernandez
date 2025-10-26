@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Unidades</h3>
            @can('unidades.create')
                <a href="{{ route('admin.unidades.create') }}" class="btn btn-primary">Nueva</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre o abreviatura">
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
                    <th>Abreviatura</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($unidades as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>
                            @can('unidades.view')
                                <a class="text-decoration-none" href="{{ route('admin.unidades.show',$u) }}">{{ $u->nombre }}</a>
                            @else
                                {{ $u->nombre }}
                            @endcan
                        </td>
                        <td class="text-muted">{{ $u->abreviatura }}</td>
                        <td class="text-end">
                            @can('unidades.update')
                                <a href="{{ route('admin.unidades.edit',$u) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('unidades.delete')
                                <form action="{{ route('admin.unidades.destroy',$u) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar unidad?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Sin unidades</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $unidades->links() }}
        </div>
    </div>
@endsection
