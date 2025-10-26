@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Nueva dirección</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.direcciones.store') }}" method="post">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Calle</label>
                    <input type="text" name="calle" class="form-control @error('calle') is-invalid @enderror" value="{{ old('calle') }}" required maxlength="150">
                    @error('calle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Colonia</label>
                    <input type="text" name="colonia" class="form-control @error('colonia') is-invalid @enderror" value="{{ old('colonia') }}" maxlength="150">
                    @error('colonia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Municipio (opcional)</label>
                    <select name="municipios_id" class="form-select @error('municipios_id') is-invalid @enderror">
                        <option value="">— Sin municipio —</option>
                        @foreach($municipios as $m)
                            <option value="{{ $m->id }}" {{ old('municipios_id')==$m->id?'selected':'' }}>{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                    @error('municipios_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.direcciones.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
@endsection

