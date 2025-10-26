@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Proveedores</h3>
            @can('proveedores.create')
                <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">Nuevo</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre, apellidos o teléfono">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="activo" {{ $estado==='activo'?'selected':'' }}>Activo</option>
                    <option value="inactivo" {{ $estado==='inactivo'?'selected':'' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([5,10,15,25,50] as $n)
                        <option value="{{ $n }}" {{ (int)$perPage===$n ? 'selected':'' }}>{{ $n }} / pág.</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">Filtrar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Persona</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($proveedores as $pv)
                    <tr>
                        <td>{{ $pv->id }}</td>
                        <td>
                            @can('proveedores.view')
                                <a class="text-decoration-none" href="{{ route('admin.proveedores.show',$pv) }}">
                                    {{ $pv->persona?->nombre }} {{ $pv->persona?->ap }} {{ $pv->persona?->am }}
                                </a>
                            @else
                                {{ $pv->persona?->nombre }} {{ $pv->persona?->ap }} {{ $pv->persona?->am }}
                            @endcan
                        </td>
                        <td class="text-muted">{{ $pv->persona?->telefono ?? '—' }}</td>
                        <td>
              <span class="badge text-bg-{{ $pv->estado==='activo'?'success':'secondary' }}">
                {{ ucfirst($pv->estado) }}
              </span>
                        </td>
                        <td class="text-end">
                            @can('proveedores.update')
                                <a href="{{ route('admin.proveedores.edit',$pv) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('proveedores.delete')
                                <form action="{{ route('admin.proveedores.destroy',$pv) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar proveedor?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin proveedores</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $proveedores->links() }}
        </div>
    </div>
@endsection
