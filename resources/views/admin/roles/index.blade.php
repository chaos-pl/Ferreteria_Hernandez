@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Roles</h3>
            @can('roles.create')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Nuevo rol</a>
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
                    <th># Permisos</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($roles as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>
                            @can('roles.view')
                                <a class="text-decoration-none" href="{{ route('admin.roles.show',$r) }}">{{ $r->name }}</a>
                            @else
                                {{ $r->name }}
                            @endcan
                        </td>
                        <td class="text-muted">{{ $r->permissions()->count() }}</td>
                        <td class="text-end">
                            @can('roles.update')
                                <a href="{{ route('admin.roles.edit',$r) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('roles.delete')
                                @if($r->name !== 'admin')
                                    <form action="{{ route('admin.roles.destroy',$r) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar rol?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Sin roles</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
