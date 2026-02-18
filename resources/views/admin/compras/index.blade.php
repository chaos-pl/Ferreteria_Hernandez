@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Compras</h3>
            @can('compras.create')
                <a href="{{ route('admin.compras.create') }}" class="btn btn-primary">Nueva</a>
            @endcan
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por ID de compra o nombre de proveedor">
            </div>
            <div class="col-md-3">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([5,10,15,25,50] as $n)
                        <option value="{{ $n }}" {{ (int)$perPage===$n ? 'selected':'' }}>{{ $n }} por página</option>
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
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($compras as $c)
                    <tr>
                        <td>#{{ $c->id }}</td>

                        <td>
                            <strong>{{ $c->proveedor?->persona?->nombre ?? '—' }}</strong>
                            <div class="text-muted small">
                                {{-- Mostrar hasta 2 productos --}}
                                @php
                                    $productos = $c->detalles
                                                    ->map(fn($d) => $d->asignacion?->producto?->nombre)
                                                    ->filter()
                                                    ->take(2)
                                                    ->join(', ');
                                @endphp

                                {{ $productos }}

                                @if($c->detalles->count() > 2)
                                    +{{ $c->detalles->count() - 2 }} más
                                @endif

                                {{-- Cantidad total --}}
                                • {{ $c->detalles->sum('cantidad') }} artículos
                            </div>
                        </td>

                        <td>{{ optional($c->fecha_compra)->format('Y-m-d H:i') }}</td>

                        <td>
        <span class="badge
            @if($c->estado === 'ordenada') bg-warning
            @elseif($c->estado === 'recibida') bg-success
            @else bg-secondary @endif">
            {{ $c->estado }}
        </span>
                        </td>

                        <td class="text-end">${{ number_format($c->total,2) }}</td>

                        <td class="text-end">
                            @can('compras.view')
                                <a href="{{ route('admin.compras.show',$c) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            @endcan

                            @can('compras.update')
                                <a href="{{ route('admin.compras.edit',$c) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan

                            @can('compras.delete')
                                <form action="{{ route('admin.compras.destroy',$c) }}" method="post" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar compra?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty

                    <tr><td colspan="6" class="text-center text-muted py-4">Sin compras</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $compras->links() }}
        </div>
    </div>
@endsection
