@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Marcas</h3>
            @can('marcas.create')
                <a href="{{ route('admin.marcas.create') }}" class="btn btn-primary">Nueva</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre">
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
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($marcas as $m)
                    <tr>
                        <td>{{ $m->id }}</td>
                        <td>
                            @can('marcas.view')
                                <a class="text-decoration-none" href="{{ route('admin.marcas.show',$m) }}">{{ $m->nombre }}</a>
                            @else
                                {{ $m->nombre }}
                            @endcan
                        </td>
                        <td class="text-end">
                            @can('marcas.update')
                                <a href="{{ route('admin.marcas.edit',$m) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('marcas.delete')
                                <form action="{{ route('admin.marcas.destroy',$m) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar marca?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Sin marcas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $marcas->links() }}
        </div>
    </div>
@endsection

