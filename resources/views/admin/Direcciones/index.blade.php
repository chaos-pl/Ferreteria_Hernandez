@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Direcciones</h3>
            @can('direcciones.create')
                <a href="{{ route('admin.direcciones.create') }}" class="btn btn-primary">Nueva</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por calle o colonia">
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
                    <th>Calle</th>
                    <th>Colonia</th>
                    <th>Municipio</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($direcciones as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>
                            @can('direcciones.view')
                                <a class="text-decoration-none" href="{{ route('admin.direcciones.show',$d) }}">{{ $d->calle }}</a>
                            @else
                                {{ $d->calle }}
                            @endcan
                        </td>
                        <td class="text-muted">{{ $d->colonia ?? '—' }}</td>
                        <td class="text-muted">{{ $d->municipio->nombre ?? '—' }}</td>
                        <td class="text-end">
                            @can('direcciones.update')
                                <a href="{{ route('admin.direcciones.edit',$d) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('direcciones.delete')
                                <form action="{{ route('admin.direcciones.destroy',$d) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar dirección?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin direcciones</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $direcciones->links() }}
        </div>
    </div>
@endsection
