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
                <div class="col-md-3"><strong>Fecha:</strong> {{ $venta->created_at->format('Y-m-d H:i') }}</div>
                <div class="col-md-3"><strong>Estado:</strong> <span class="badge text-bg-secondary">{{ $venta->estado }}</span></div>
                <div class="col-md-3"><strong>Subtotal:</strong> ${{ number_format($venta->subtotal,2) }}</div>
                <div class="col-md-3"><strong>Descuentos:</strong> ${{ number_format($venta->descuentos,2) }}</div>
                <div class="col-md-3"><strong>Impuestos:</strong> ${{ number_format($venta->impuestos,2) }}</div>
                <div class="col-md-3"><strong>Total:</strong> ${{ number_format($venta->total,2) }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Detalle de la venta</div>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio base</th>
                        <th>Descuento</th>
                        <th>Precio final</th>
                        <th>Subtotal</th>
                        <th>Impuesto</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venta->detalles as $d)

                        @php
                            $app = $d->asignaProductoProveedor;

                            // Producto puede ser null
                            $producto = $app->producto ?? null;

                            // Proveedor puede ser null
                            $proveedor = $app->proveedor->persona ?? null;

                            // precios
                            $precioBase = $producto->precio ?? 0;

                            $descuento = $precioBase > 0
                                ? round(100 - (($d->precio_unit / $precioBase) * 100), 2)
                                : 0;

                            $precioFinal = $d->precio_unit;
                            $subtotal = $d->subtotal;
                            $impuesto = round($precioFinal * 0.16, 2);
                        @endphp

                        <tr>
                            <td>{{ $d->id }}</td>

                            <td>
                                {{-- NOMBRE DEL PRODUCTO --}}
                                @if($producto)
                                    {{ $producto->nombre }}
                                @else
                                    <span class="text-danger">Producto eliminado</span>
                                @endif

                                <br>

                                {{-- PROVEEDOR --}}
                                <small class="text-muted">
                                    Prov:
                                    @if($proveedor)
                                        {{ $proveedor->nombre }}
                                    @else
                                        Proveedor eliminado
                                    @endif
                                </small>
                            </td>

                            <td>{{ $d->cantidad }}</td>

                            <td>${{ number_format($precioBase, 2) }}</td>

                            <td>
                                @if($descuento > 0)
                                    <span class="text-success fw-bold">{{ $descuento }}%</span>
                                @else
                                    <span class="text-muted">0%</span>
                                @endif
                            </td>

                            <td>${{ number_format($precioFinal, 2) }}</td>
                            <td>${{ number_format($subtotal, 2) }}</td>
                            <td>${{ number_format($impuesto, 2) }}</td>
                        </tr>

                    @endforeach

                    @if($venta->detalles->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Sin renglones
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
