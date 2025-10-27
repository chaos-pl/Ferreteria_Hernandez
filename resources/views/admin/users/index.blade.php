@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Usuarios</h3>
            @can('users.create')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Nuevo</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre o email">
            </div>
            <div class="col-md-3">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([5,10,15,25,50] as $n)
                        <option value="{{ $n }}" @selected((int)$perPage === $n)>{{ $n }} por página</option>
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
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Roles</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>{{ $u->id }}</td>

                        {{-- Enlace al show desde el nombre (estilo Unidades) --}}
                        <td>
                            @can('users.view')
                                <a class="text-decoration-none" href="{{ route('admin.users.show',$u) }}">{{ $u->name }}</a>
                            @else
                                {{ $u->name }}
                            @endcan
                        </td>

                        <td class="text-muted">{{ $u->email }}</td>

                        <td>
                            <span class="badge text-bg-{{ $u->estado === 'activo' ? 'success' : 'secondary' }}">{{ $u->estado }}</span>
                        </td>

                        <td class="small text-muted">{{ $u->roles->pluck('name')->join(', ') }}</td>

                        <td class="text-end">
                            @can('users.update')
                                <a href="{{ route('admin.users.edit',$u) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('users.delete')
                                <form action="{{ route('admin.users.destroy',$u) }}" method="post" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar usuario?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin usuarios</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $users->links() }}</div>
    </div>
@endsection
