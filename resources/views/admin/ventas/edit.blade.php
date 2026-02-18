@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">

        <div class="d-flex justify-content-between mb-3">
            <h3>Editar venta #{{ $venta->id }}</h3>
            <a href="{{ route('admin.ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('admin.ventas.update',$venta) }}">
            @csrf @method('PUT')

            <div class="card mb-3">
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Cliente</label>
                        <select name="users_id" class="form-select">
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" {{ $venta->users_id==$u->id?'selected':'' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="pagada" {{ $venta->estado=='pagada' ? 'selected':'' }}>Pagada</option>
                            <option value="cancelada" {{ $venta->estado=='cancelada' ? 'selected':'' }}>Cancelada</option>
                        </select>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary">Actualizar</button>

        </form>
    </div>
@endsection
