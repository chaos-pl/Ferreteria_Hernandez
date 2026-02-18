@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container py-4">
        <h3 class="mb-3">Nueva compra</h3>
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif


        <form method="post" action="{{ route('admin.compras.store') }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Proveedor</label>
                    <select name="proveedores_id" class="form-select" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}">
                                #{{ $p->id }} — {{ $p->persona?->nombre ?? 'Proveedor' }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedores_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr>

            <h5 class="mb-2">Renglones</h5>
            @error('asigna_ids') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

            <div id="items">
                <div class="row g-2 align-items-end item-row mb-2">

                    <div class="col-md-6">
                        <label class="form-label">Producto – Proveedor (SKU)</label>
                        <select name="asigna_ids[]" class="form-select" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($asignaciones as $a)
                                <option value="{{ $a->id }}">
                                    #{{ $a->id }} — {{ $a->producto?->nombre }} · {{ $a->proveedor?->persona?->nombre }}
                                    {{ $a->sku_proveedor ? " (SKU: $a->sku_proveedor)" : ''}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" class="form-control" name="cantidades[]" min="1" value="1" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Costo unit.</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="costos[]" value="0" required>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100 add-row">Agregar</button>
                    </div>

                </div>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.compras.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('click', function(e){
            if(e.target.classList.contains('add-row')){
                const wrap = document.getElementById('items');
                const base = e.target.closest('.item-row');
                const clone = base.cloneNode(true);

                clone.querySelectorAll('input').forEach(i => {
                    if(i.name.includes('cantidades')) i.value=1;
                    if(i.name.includes('costos')) i.value=0;
                });

                clone.querySelector('.add-row').outerHTML =
                    '<button type="button" class="btn btn-outline-danger w-100 remove-row">Quitar</button>';

                wrap.appendChild(clone);
            }

            if(e.target.classList.contains('remove-row')){
                e.target.closest('.item-row').remove();
            }
        });
    </script>
@endsection
