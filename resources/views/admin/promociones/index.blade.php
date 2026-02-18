@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Promociones</h3>
            <a href="{{ route('admin.promociones.create') }}" class="btn btn-primary">Nueva</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descuento</th>
                <th>Vigencia</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($promos as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->descuento }}%</td>
                    <td>{{ $p->fecha_inicio }} — {{ $p->fecha_fin }}</td>

                    <td class="text-end">
                        <a href="{{ route('admin.promociones.edit',$p) }}" class="btn btn-sm btn-outline-primary">Editar</a>

                        <form action="{{ route('admin.promociones.destroy',$p) }}" method="post" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">
                                Eliminar
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $promos->links() }}
    </div>
@endsection
