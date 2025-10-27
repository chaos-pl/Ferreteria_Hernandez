@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Editar usuario</h3>

        <form action="{{ route('admin.users.update',$user) }}" method="post">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Persona (opcional)</label>
                    <select name="personas_id" class="form-select">
                        <option value="">— Selecciona —</option>
                        @foreach($personas as $p)
                            <option value="{{ $p->id }}" @selected(old('personas_id',$user->personas_id)==$p->id)>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        @foreach(['activo','inactivo'] as $e)
                            <option value="{{ $e }}" @selected(old('estado',$user->estado)===$e)>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input name="name" value="{{ old('name',$user->name) }}" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password (opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmación</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label me-2">Roles</label>
                    <div class="d-flex flex-wrap gap-3">
                        @php($checked = old('roles', $user->roles->pluck('name')->toArray()))
                        @foreach($roles as $r)
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $r->name }}"
                                    @checked(collect($checked)->contains($r->name))>
                                <span class="form-check-label">{{ $r->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="verified" value="1"
                            @checked(old('verified', (bool)$user->email_verified_at))>
                        <span class="form-check-label">Marcar email como verificado</span>
                    </label>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Actualizar</button>
                <a href="{{ route('admin.users.show',$user) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
