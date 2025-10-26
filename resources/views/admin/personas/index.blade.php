@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Personas</h3>
            @can('personas.create')
                <a href="{{ route('admin.personas.create') }}" class="btn btn-primary">Nueva</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre o teléfono">
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
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($personas as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            @can('personas.view')
                                <a class="text-decoration-none" href="{{ route('admin.personas.show',$p) }}">{{ $p->nombre_completo }}</a>
                            @else
                                {{ $p->nombre_completo }}
                            @endcan
                        </td>
                        <td>{{ $p->telefono ?? '—' }}</td>
                        <td class="text-muted">
                            {{ $p->direccion ? ($p->direccion->calle.' · '.$p->direccion->colonia) : '—' }}
                        </td>
                        <td class="text-end">
                            @can('personas.update')
                                <a href="{{ route('admin.personas.edit',$p) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('personas.delete')
                                <form action="{{ route('admin.personas.destroy',$p) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar persona?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin personas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $personas->links() }}
        </div>
    </div>
@endsection
