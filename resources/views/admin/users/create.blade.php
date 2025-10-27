@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Nuevo usuario</h3>

        <form action="{{ route('admin.users.store') }}" method="post">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Persona (opcional)</label>
                    <select name="personas_id" class="form-select">
                        <option value="">— Selecciona —</option>
                        @foreach($personas as $p)
                            <option value="{{ $p->id }}" @selected(old('personas_id')==$p->id)>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="activo" @selected(old('estado','activo')==='activo')>Activo</option>
                        <option value="inactivo" @selected(old('estado')==='inactivo')>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmación</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label me-2">Roles</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($roles as $r)
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $r->name }}"
                                    @checked(collect(old('roles',[]))->contains($r->name))>
                                <span class="form-check-label">{{ $r->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="verified" value="1" @checked(old('verified'))>
                        <span class="form-check-label">Marcar email como verificado</span>
                    </label>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
