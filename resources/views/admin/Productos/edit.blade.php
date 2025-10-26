@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Editar producto</h3>

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('admin.productos.update',$producto) }}" method="post" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre',$producto->nombre) }}" required maxlength="150">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Categoría</label>
                    <select name="categorias_id" class="form-select @error('categorias_id') is-invalid @enderror" required>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" {{ (int)old('categorias_id',$producto->categorias_id)===(int)$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categorias_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Unidad</label>
                    <select name="unidades_id" class="form-select @error('unidades_id') is-invalid @enderror" required>
                        @foreach($unidades as $u)
                            <option value="{{ $u->id }}" {{ (int)old('unidades_id',$producto->unidades_id)===(int)$u->id?'selected':'' }}>{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                    @error('unidades_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Marca (opcional)</label>
                    <select name="marcas_id" class="form-select @error('marcas_id') is-invalid @enderror">
                        <option value="">— Sin marca —</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->id }}" {{ (int)old('marcas_id',$producto->marcas_id)===(int)$m->id?'selected':'' }}>{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                    @error('marcas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Precio</label>
                    <input type="number" name="precio" step="0.01" min="0" class="form-control @error('precio') is-invalid @enderror"
                           value="{{ old('precio',$producto->precio) }}" required>
                    @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Existencias</label>
                    <input type="number" name="existencias" min="0" class="form-control @error('existencias') is-invalid @enderror"
                           value="{{ old('existencias',$producto->existencias) }}" required>
                    @error('existencias')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        @foreach(['activo','inactivo'] as $opt)
                            <option value="{{ $opt }}" {{ old('estado',$producto->estado)===$opt?'selected':'' }}>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion',$producto->descripcion) }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Imagen (opcional)</label>
                    @if($producto->imagen_url)
                        <div class="mb-2"><img src="{{ $producto->imagen_url }}" alt="" style="width:90px;height:90px;object-fit:cover;border-radius:10px"></div>
                    @endif
                    <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*">
                    @error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
@endsection
