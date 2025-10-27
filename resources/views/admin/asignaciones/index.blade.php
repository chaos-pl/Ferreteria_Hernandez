@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Asignaciones producto–proveedor</h3>
            <a href="{{ route('admin.asignaciones.create') }}" class="btn btn-primary">Nueva</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form class="row g-2 mb-3" method="get">
            <div class="col-md-3">
                <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Buscar por SKU proveedor">
            </div>
            <div class="col-md-4">
                <select name="producto" class="form-select" onchange="this.form.submit()">
                    <option value="">— Todos los productos —</option>
                    @foreach($productos as $p)
                        <option value="{{ $p->id }}" @selected((string)$pid===(string)$p->id)>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="proveedor" class="form-select" onchange="this.form.submit()">
                    <option value="">— Todos los proveedores —</option>
                    @foreach($proveedores as $v)
                        <option value="{{ $v->id }}" @selected((string)$vid===(string)$v->id)>
                            #{{ $v->id }} — {{ $v->persona?->nombre }} {{ $v->persona?->ap }} {{ $v->persona?->am }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([10,15,25,50] as $n)
                        <option value="{{ $n }}" @selected((int)$per===$n)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Proveedor</th>
                    <th>SKU proveedor</th>
                    <th>Plazo (d)</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($asignaciones as $a)
                    <tr>
                        <td>{{ $a->id }}</td>

                        {{-- Enlace al show como en Unidades --}}
                        <td>
                            @can('asignaciones.view')
                                <a class="text-decoration-none"
                                   href="{{ route('admin.asignaciones.show',$a) }}">{{ $a->producto?->nombre }}</a>
                            @else
                                {{ $a->producto?->nombre }}
                            @endcan
                        </td>

                        <td class="text-muted">
                            #{{ $a->proveedor?->id }} — {{ $a->proveedor?->persona?->nombre }} {{ $a->proveedor?->persona?->ap }}
                        </td>
                        <td class="text-muted">{{ $a->sku_proveedor ?: '—' }}</td>
                        <td class="text-muted">{{ $a->plazo_entrega ?: '—' }}</td>

                        <td class="text-end">
                            @can('asignaciones.update')
                                <a href="{{ route('admin.asignaciones.edit',$a) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('asignaciones.delete')
                                <form class="d-inline" method="post" action="{{ route('admin.asignaciones.destroy',$a) }}"
                                      onsubmit="return confirm('¿Eliminar asignación?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin registros</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $asignaciones->links() }}</div>
    </div>
@endsection
