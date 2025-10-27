@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Compra #{{ $compra->id }}</h3>
            <div class="d-flex gap-2">
                @can('compras.update')
                    <a href="{{ route('admin.compras.edit',$compra) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                @endcan
                <a href="{{ route('admin.compras.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

        <div class="card mb-3">
            <div class="card-body">
                <p class="mb-2"><strong>Proveedor:</strong> {{ $compra->proveedor?->persona?->nombre ?? '—' }}</p>
                <p class="mb-2"><strong>Fecha:</strong> {{ optional($compra->fecha_compra)->format('Y-m-d H:i') }}</p>
                <p class="mb-2"><strong>Estado:</strong> <span class="badge text-bg-light">{{ $compra->estado }}</span></p>
                <p class="mb-0"><strong>Operador:</strong> {{ $compra->usuario?->name ?? '—' }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Proveedor</th>
                    <th class="text-end">Cant.</th>
                    <th class="text-end">Costo unit.</th>
                    <th class="text-end">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @forelse($compra->detalles as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $d->asignacion?->producto?->nombre ?? '—' }}</td>
                        <td>{{ $d->asignacion?->proveedor?->persona?->nombre ?? '—' }}</td>
                        <td class="text-end">{{ $d->cantidad }}</td>
                        <td class="text-end">${{ number_format($d->costo_unit,2) }}</td>
                        <td class="text-end">${{ number_format($d->subtotal,2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin renglones</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total</th>
                    <th class="text-end">${{ number_format($compra->total,2) }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
