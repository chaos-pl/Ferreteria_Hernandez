@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Nuevo rol</h3>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form method="post" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <label class="form-label fw-semibold">Nombre del rol</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="ej. control_ventas" required>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Permisos</h5>

                    @forelse($perms as $module => $list)
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
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input chk-{{ $module }}" type="checkbox" name="perms[]" value="{{ $p->name }}" id="p_{{ md5($p->name) }}">
                                            <label class="form-check-label" for="p_{{ md5($p->name) }}">{{ $p->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No hay permisos definidos.</p>
                    @endforelse
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary">Guardar rol</button>
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
