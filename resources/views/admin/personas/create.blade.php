@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Nueva persona</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.personas.store') }}" method="post">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" required maxlength="120">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido paterno</label>
                    <input type="text" name="ap" class="form-control @error('ap') is-invalid @enderror"
                           value="{{ old('ap') }}" maxlength="120">
                    @error('ap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido materno</label>
                    <input type="text" name="am" class="form-control @error('am') is-invalid @enderror"
                           value="{{ old('am') }}" maxlength="150">
                    @error('am')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono') }}" maxlength="20">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Dirección (opcional)</label>
                    <select name="direcciones_id" class="form-select @error('direcciones_id') is-invalid @enderror">
                        <option value="">— Sin dirección —</option>
                        @foreach($direcciones as $d)
                            <option value="{{ $d->id }}" {{ old('direcciones_id')==$d->id?'selected':'' }}>
                                #{{ $d->id }} — {{ $d->calle }} {{ $d->colonia ? '· '.$d->colonia : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('direcciones_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.personas.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
@endsection
