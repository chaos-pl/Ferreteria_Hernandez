@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Nuevo proveedor</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.proveedores.store') }}" method="post">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Persona</label>
                    <select name="personas_id" class="form-select @error('personas_id') is-invalid @enderror" required>
                        <option value="">— Selecciona —</option>
                        @foreach($personas as $p)
                            <option value="{{ $p->id }}" {{ old('personas_id')==$p->id ? 'selected':'' }}>
                                #{{ $p->id }} — {{ $p->nombre }} {{ $p->ap }} {{ $p->am }}
                            </option>
                        @endforeach
                    </select>
                    @error('personas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        @foreach(['activo','inactivo'] as $opt)
                            <option value="{{ $opt }}" {{ old('estado','activo')===$opt?'selected':'' }}>
                                {{ ucfirst($opt) }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
@endsection
