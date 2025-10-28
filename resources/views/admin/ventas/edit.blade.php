@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Editar venta #{{ $venta->id }}</h3>
            <a href="{{ route('admin.ventas.show',$venta) }}" class="btn btn-secondary">Volver</a>
        </div>

        <form action="{{ route('admin.ventas.update',$venta) }}" method="post">
            @csrf @method('PUT')

            <div class="card mb-3">
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cliente (user)</label>
                        <select name="users_id" class="form-select" required>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" {{ old('users_id',$venta->users_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('users_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha venta</label>
                        <input type="datetime-local" name="fecha_venta" class="form-control"
                               value="{{ old('fecha_venta', $venta->fecha_venta?->format('Y-m-d\TH:i')) }}" required>
                        @error('fecha_venta') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            @foreach(['carrito','pagada','enviada','entregada','cancelada'] as $e)
                                <option {{ old('estado',$venta->estado)===$e ? 'selected':'' }} value="{{ $e }}">{{ ucfirst($e) }}</option>
                            @endforeach
                        </select>
                        @error('estado') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Descuentos</label>
                        <input type="number" step="0.01" name="descuentos" class="form-control" value="{{ old('descuentos',$venta->descuentos) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Impuestos</label>
                        <input type="number" step="0.01" name="impuestos" class="form-control" value="{{ old('impuestos',$venta->impuestos) }}">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold">Detalle</div>
                <div class="card-body">
                    <div id="rows">
                        @foreach(old('app_id', $venta->detalles->pluck('asigna_productos_proveedores_id')->toArray()) as $i => $oldApp)
                            <div class="row g-2 align-items-end mb-2 detalle-row">
                                <div class="col-md-6">
                                    <label class="form-label">Asignación producto–proveedor</label>
                                    <select name="app_id[]" class="form-select" required>
                                        @foreach($apps as $a)
                                            <option value="{{ $a->id }}" {{ (int)$oldApp===$a->id?'selected':'' }}>
                                                #{{ $a->id }} — {{ $a->producto->nombre ?? 'Producto' }} / {{ $a->proveedor->nombre_comercial ?? 'Proveedor' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Cantidad</label>
                                    @php $cant = old('cantidad')[$i] ?? ($venta->detalles[$i]->cantidad ?? 1); @endphp
                                    <input type="number" min="1" name="cantidad[]" class="form-control" value="{{ $cant }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Precio unit.</label>
                                    @php $pu = old('precio_unit')[$i] ?? ($venta->detalles[$i]->precio_unit ?? 0); @endphp
                                    <input type="number" step="0.01" min="0" name="precio_unit[]" class="form-control" value="{{ $pu }}" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger w-100 btn-remove">Quitar</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="btn-add" class="btn btn-outline-secondary mt-2">Agregar renglón</button>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // mismo JS que en create
        document.getElementById('btn-add').addEventListener('click', () => {
            const rows = document.getElementById('rows');
            const tpl  = rows.querySelector('.detalle-row');
            const node = tpl.cloneNode(true);
            node.querySelectorAll('input').forEach(i => i.value = i.name.includes('cantidad') ? 1 : 0);
            node.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
            rows.appendChild(node);
            hookRemove(node.querySelector('.btn-remove'));
        });
        function hookRemove(btn){
            btn.addEventListener('click', (e) => {
                const row = e.target.closest('.detalle-row');
                const rows = document.querySelectorAll('.detalle-row');
                if(rows.length > 1) row.remove();
            });
        }
        document.querySelectorAll('.btn-remove').forEach(hookRemove);
    </script>
@endsection
