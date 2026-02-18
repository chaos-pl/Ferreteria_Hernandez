@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Ventas</h3>
            @can('ventas.create')
                <a href="{{ route('admin.ventas.create') }}" class="btn btn-primary">Nueva venta</a>
            @endcan
        </div>

        @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por #, estado o cliente">
            </div>
            <div class="col-md-3">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([5,10,15,25,50] as $n)
                        <option value="{{ $n }}" {{ (int)request('per_page',10)===$n ? 'selected':'' }}>{{ $n }} por página</option>
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
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($ventas as $v)
                    <tr>
                        {{-- Ya NO es link: solo texto --}}
                        <td>#{{ $v->id }}</td>

                        <td>{{ $v->user?->name ?? '—' }}</td>
                        <td>{{ $v->created_at->format('Y-m-d H:i') }}</td>
                        <td><span class="badge text-bg-secondary">{{ $v->estado }}</span></td>
                        <td class="text-end">${{ number_format($v->total,2) }}</td>

                        <td class="text-end">
                            @can('ventas.view')
                                <a href="{{ route('admin.ventas.show',$v) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            @endcan

                            @can('ventas.update')
                                <a href="{{ route('admin.ventas.edit',$v) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan

                            @can('ventas.delete')
                                <form action="{{ route('admin.ventas.destroy',$v) }}" method="post" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar venta?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin ventas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $ventas->links() }}
        </div>
    </div>
@endsection
