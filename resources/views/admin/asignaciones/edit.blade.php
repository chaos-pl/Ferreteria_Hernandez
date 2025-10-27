@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Editar asignación #{{ $asignacion->id }}</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="post" action="{{ route('admin.asignaciones.update',$asignacion) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Producto</label>
                    <select name="productos_id" class="form-select" required>
                        @foreach($productos as $p)
                            <option value="{{ $p->id }}"
                                @selected((int)old('productos_id',$asignacion->productos_id)===(int)$p->id)>
                                {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('productos_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Proveedor</label>
                    <select name="proveedores_id" class="form-select" required>
                        @foreach($proveedores as $v)
                            <option value="{{ $v->id }}"
                                @selected((int)old('proveedores_id',$asignacion->proveedores_id)===(int)$v->id)>
                                #{{ $v->id }} — {{ $v->persona?->nombre }} {{ $v->persona?->ap }} {{ $v->persona?->am }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedores_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">SKU proveedor (opcional)</label>
                    <input type="text" name="sku_proveedor" class="form-control"
                           value="{{ old('sku_proveedor',$asignacion->sku_proveedor) }}" maxlength="80">
                    @error('sku_proveedor')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Plazo de entrega (días, opcional)</label>
                    <input type="number" name="plazo_entrega" class="form-control" min="0" max="65535"
                           value="{{ old('plazo_entrega',$asignacion->plazo_entrega) }}">
                    @error('plazo_entrega')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary">Actualizar</button>
                <a class="btn btn-secondary" href="{{ route('admin.asignaciones.index') }}">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
