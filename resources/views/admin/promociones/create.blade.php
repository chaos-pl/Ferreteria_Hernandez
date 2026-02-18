@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">

        <h3 class="mb-3">Crear promoción</h3>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.promociones.store') }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Descuento (%)</label>
                    <input type="number" name="descuento" class="form-control"
                           value="{{ old('descuento') }}" min="1" max="100" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Inicio</label>
                    <input type="date" name="fecha_inicio"
                           class="form-control" value="{{ old('fecha_inicio') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Fin</label>
                    <input type="date" name="fecha_fin"
                           class="form-control" value="{{ old('fecha_fin') }}" required>
                </div>
            </div>

            <hr>

            <h5 class="mb-2">Productos afectados</h5>
            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">

                @foreach($productos as $p)
                    <div class="form-check mb-1">
                        <input class="form-check-input"
                               type="checkbox"
                               name="productos[]"
                               value="{{ $p->id }}"
                               id="prod{{ $p->id }}"
                            {{ in_array($p->id, old('productos',[])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="prod{{ $p->id }}">
                            {{ $p->nombre }}
                        </label>
                    </div>
                @endforeach

            </div>

            <div class="text-end mt-3">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.promociones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>

    </div>
@endsection
