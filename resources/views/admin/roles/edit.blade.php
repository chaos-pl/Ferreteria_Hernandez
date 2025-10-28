@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Editar rol: {{ $role->name }}</h3>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form method="post" action="{{ route('admin.roles.update',$role) }}">
            @csrf @method('PUT')

            <div class="card mb-3">
                <div class="card-body">
                    <label class="form-label fw-semibold">Nombre del rol</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name',$role->name) }}"
                        {{ $role->name === 'admin' ? 'readonly' : '' }}>
                    @if($role->name === 'admin')
                        <div class="form-text">El rol <strong>admin</strong> no se puede renombrar.</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Permisos</h5>

                    @foreach($perms as $module => $list)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-uppercase">{{ $module }}</strong>
                                <div class="small">
                                    <a href="#" onclick="toggleModule('{{ $module }}',true);return false;">Marcar todo</a> ·
                                    <a href="#" onclick="toggleModule('{{ $module }}',false);return false;">Desmarcar</a>
                                </div>
                            </div>
                            <div class="row g-2" id="box-{{ $module }}">
                                @foreach($list as $p)
                                    @php $checked = in_array($p->name, $assigned ?? [], true); @endphp
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input chk-{{ $module }}" type="checkbox" name="perms[]" value="{{ $p->name }}"
                                                   id="p_{{ md5($p->name) }}" {{ $checked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="p_{{ md5($p->name) }}">{{ $p->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        @can('roles.delete')
                            @if($role->name !== 'admin')
                                <form action="{{ route('admin.roles.destroy',$role) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar rol?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger">Eliminar</button>
                                </form>
                            @endif
                        @endcan
                    </div>
                    <button class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleModule(mod, val){
            document.querySelectorAll('.chk-'+mod).forEach(cb=>{ cb.checked = !!val; });
        }
    </script>
@endsection
