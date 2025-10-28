@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Venta #{{ $venta->id }}</h3>
            <div class="d-flex gap-2">
                @can('ventas.update')
                    <a href="{{ route('admin.ventas.edit',$venta) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.ventas.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body row g-3">
                <div class="col-md-3"><strong>Cliente:</strong> {{ $venta->user?->name ?? '—' }}</div>
                <div class="col-md-3"><strong>Fecha:</strong> {{ $venta->fecha_venta->format('Y-m-d H:i') }}</div>
                <div class="col-md-3"><strong>Estado:</strong> <span class="badge text-bg-secondary">{{ $venta->estado }}</span></div>
                <div class="col-md-3"><strong>Subtotal:</strong> ${{ number_format($venta->subtotal,2) }}</div>
                <div class="col-md-3"><strong>Descuentos:</strong> ${{ number_format($venta->descuentos,2) }}</div>
                <div class="col-md-3"><strong>Impuestos:</strong> ${{ number_format($venta->impuestos,2) }}</div>
                <div class="col-md-3"><strong>Total:</strong> ${{ number_format($venta->total,2) }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Detalle</div>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Asignación</th>
                        <th>Cantidad</th>
                        <th>Precio unit.</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venta->detalles as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>#{{ $d->asigna_productos_proveedores_id }} — {{ $d->asignaProductoProveedor->producto->nombre ?? 'Producto' }} / {{ $d->asignaProductoProveedor->proveedor->nombre_comercial ?? 'Proveedor' }}</td>
                            <td>{{ $d->cantidad }}</td>
                            <td>${{ number_format($d->precio_unit,2) }}</td>
                            <td class="text-end">${{ number_format($d->subtotal,2) }}</td>
                        </tr>
                    @endforeach
                    @if($venta->detalles->isEmpty())
                        <tr><td colspan="5" class="text-center text-muted py-4">Sin renglones</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
