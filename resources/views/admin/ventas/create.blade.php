@extends('layouts.dashboard')

@section('dashboard-content')

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="container py-4">
        <div class="d-flex justify-content-between mb-3">
            <h3>Nueva venta</h3>
            <a href="{{ route('admin.ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('admin.ventas.store') }}">
            @csrf

            {{-- CLIENTE --}}
            <div class="card mb-3">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Cliente</label>
                        <select name="users_id" class="form-select" required>
                            <option value="" disabled selected>-- Selecciona --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- DETALLE --}}
            <div class="card">
                <div class="card-header fw-bold">Detalle de venta</div>
                <div class="card-body">

                    <div id="rows">
                        <div class="row g-3 detalle-row mb-3">

                            {{-- SELECT PRODUCTO --}}
                            <select name="app_id[]" class="form-select app-select" required>
                                <option value="" disabled selected>-- Selecciona el producto --</option>

                                @foreach($apps as $a)

                                    @php
                                        // EVITA EL ERROR SI EL PRODUCTO NO EXISTE
                                        if (!$a->producto) {
                                            continue; // salta este elemento
                                        }

                                        $promo = $a->producto->promocionActiva();
                                        $descuento = $promo ? $promo->descuento : 0;
                                    @endphp

                                    <option
                                        value="{{ $a->id }}"
                                        data-precio="{{ $a->producto->precio }}"
                                        data-descuento="{{ $descuento }}"
                                    >
                                        {{ $a->producto->nombre }}
                                        (Proveedor: {{ $a->proveedor->persona->nombre }} {{ $a->proveedor->persona->ap }})
                                    </option>

                                @endforeach

                            </select>


                            {{-- CANTIDAD --}}
                            <div class="col-md-2">
                                <label class="form-label">Cantidad</label>
                                <input type="number" name="cantidad[]" class="form-control cantidad-input" min="1" required>
                            </div>

                            {{-- INFO PRECIOS (VISUAL) --}}
                            <div class="col-md-4">
                                <div class="bg-light border p-2 rounded">
                                    <small>Precio base:</small>
                                    <div class="fw-bold precio-base">$0.00</div>

                                    <small>Descuento:</small>
                                    <div class="fw-bold text-primary descuento">0%</div>

                                    <small>Precio final:</small>
                                    <div class="fw-bold text-success precio-final">$0.00</div>

                                    <small>Impuesto (16%):</small>
                                    <div class="fw-bold text-danger impuesto">$0.00</div>
                                </div>
                            </div>

                            {{-- REMOVER --}}
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-danger btn-remove">Quitar</button>
                            </div>

                        </div>
                    </div>

                    <button type="button" id="btn-add" class="btn btn-outline-secondary mt-2">
                        Agregar renglón
                    </button>

                </div>

                <div class="card-footer text-end">
                    <button class="btn btn-primary">Guardar venta</button>
                </div>
            </div>
        </form>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function actualizarInfo(row) {
            const sel = row.querySelector('.app-select');
            const precioBase = parseFloat(sel.selectedOptions[0].dataset.precio || 0);
            const descuento = parseFloat(sel.selectedOptions[0].dataset.descuento || 0);

            const precioFinal = precioBase - (precioBase * (descuento / 100));
            const impuesto = precioFinal * 0.16;

            row.querySelector('.precio-base').textContent = '$' + precioBase.toFixed(2);
            row.querySelector('.descuento').textContent = descuento + '%';
            row.querySelector('.precio-final').textContent = '$' + precioFinal.toFixed(2);
            row.querySelector('.impuesto').textContent = '$' + impuesto.toFixed(2);
        }

        document.addEventListener('change', function(e){
            if(e.target.classList.contains('app-select')){
                const row = e.target.closest('.detalle-row');
                actualizarInfo(row);
            }
        });

        // Botón agregar renglón
        document.getElementById('btn-add').addEventListener('click', () => {
            const rows = document.getElementById('rows');
            const tpl = rows.querySelector('.detalle-row');
            const clone = tpl.cloneNode(true);

            clone.querySelectorAll('input').forEach(i => i.value = "");
            clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

            clone.querySelector('.precio-base').textContent = '$0.00';
            clone.querySelector('.descuento').textContent = '0%';
            clone.querySelector('.precio-final').textContent = '$0.00';
            clone.querySelector('.impuesto').textContent = '$0.00';

            rows.appendChild(clone);

            hookRemove(clone.querySelector('.btn-remove'));
        });

        // Botón quitar
        function hookRemove(btn){
            btn.addEventListener('click', (e) => {
                const row = e.target.closest('.detalle-row');
                if(document.querySelectorAll('.detalle-row').length > 1){
                    row.remove();
                }
            });
        }

        document.querySelectorAll('.btn-remove').forEach(hookRemove);
    </script>

@endsection
