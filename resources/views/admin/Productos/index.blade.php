@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Productos</h3>
            @can('productos.create')
                <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">Nuevo</a>
            @endcan
        </div>

        @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre o descripción">
            </div>
            <div class="col-md-2">
                <select name="categoria" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $c)
                        <option value="{{ $c->id }}" {{ (string)$cat===(string)$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="marca" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las marcas</option>
                    @foreach($marcas as $m)
                        <option value="{{ $m->id }}" {{ (string)$mar===(string)$m->id?'selected':'' }}>{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="unidad" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las unidades</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->id }}" {{ (string)$uni===(string)$u->id?'selected':'' }}>{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="activo"   {{ $estado==='activo'?'selected':'' }}>Activo</option>
                    <option value="inactivo" {{ $estado==='inactivo'?'selected':'' }}>Inactivo</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([5,10,15,25,50] as $n)
                        <option value="{{ $n }}" {{ (int)$perPage===$n ? 'selected':'' }}>{{ $n }} / pág.</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">Filtrar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Unidad</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Existencias</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($productos as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            @if($p->imagen_url)
                                <img src="{{ $p->imagen_url }}" alt="" style="width:34px;height:34px;object-fit:cover;border-radius:6px;margin-right:6px">
                            @endif
                            @can('productos.view')
                                <a class="text-decoration-none" href="{{ route('admin.productos.show',$p) }}">{{ $p->nombre }}</a>
                            @else
                                {{ $p->nombre }}
                            @endcan
                        </td>
                        <td class="text-muted">{{ $p->categoria?->nombre }}</td>
                        <td class="text-muted">{{ $p->marca?->nombre ?? '—' }}</td>
                        <td class="text-muted">{{ $p->unidad?->nombre }}</td>
                        <td class="text-end">${{ number_format($p->precio,2) }}</td>
                        <td class="text-end">{{ $p->existencias }}</td>
                        <td>
                            <span class="badge text-bg-{{ $p->estado==='activo'?'success':'secondary' }}">{{ ucfirst($p->estado) }}</span>
                        </td>
                        <td class="text-end">
                            @can('productos.update')
                                <a href="{{ route('admin.productos.edit',$p) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endcan
                            @can('productos.delete')
                                <form action="{{ route('admin.productos.destroy',$p) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar producto?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Sin productos</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $productos->links() }}
        </div>
    </div>
@endsection
