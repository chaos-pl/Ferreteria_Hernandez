@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Editar compra #{{ $compra->id }}</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('admin.compras.update',$compra) }}">
            @csrf @method('PUT')

            <div class="row g-3 mb-3">

                <div class="col-md-4">
                    <label class="form-label">Proveedor</label>
                    <input class="form-control" value="{{ $compra->proveedor->persona->nombre }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha compra</label>
                    <input class="form-control" value="{{ $compra->fecha_compra }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="ordenada" @selected($compra->estado=='ordenada')>Ordenada</option>
                        <option value="recibida" @selected($compra->estado=='recibida')>Recibida</option>
                    </select>
                </div>

            </div>

            <hr>

            <h5 class="mb-2">Renglones</h5>

            {{-- Encabezados de columnas --}}
            <div class="row g-2 mb-1 fw-bold text-secondary">
                <div class="col-md-6">Producto</div>
                <div class="col-md-2">Cantidad</div>
                <div class="col-md-2">Costo unit.</div>
            </div>

            @foreach($compra->detalles as $d)
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <input class="form-control"
                               value="#{{ $d->asignacion->id }} — {{ $d->asignacion->producto->nombre }}"
                               disabled>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control"
                               value="{{ $d->cantidad }}"
                               disabled>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control"
                               value="{{ number_format($d->costo_unit, 2) }}"
                               disabled>
                    </div>
                </div>
            @endforeach


            <div class="text-end mt-3">
                <button class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('admin.compras.show',$compra) }}" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
@endsection
