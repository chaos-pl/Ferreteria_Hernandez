{{-- Modal de vista rápida de producto --}}
<div class="modal fade" id="modalProd{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 rounded-4">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">{{ $p->nombre }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <img src="{{ $p->imagen_url ?? asset('img/noimg.png') }}"
                     class="w-100 mb-3 rounded shadow-sm"
                     style="max-height:330px; object-fit:cover;">

                <p class="text-muted">{{ $p->descripcion }}</p>

                <div class="fw-bold" style="font-size:1.5rem; color:var(--orange-700);">
                    ${{ number_format($p->precio,2) }}
                </div>

                <p class="small text-muted mt-2">
                    Categoría: {{ $p->categoria?->nombre ?? 'Sin categoría' }} <br>
                    Marca: {{ $p->marca?->nombre ?? 'Sin marca' }}
                </p>

                <hr>

                @if($p->existencias > 0)
                    <form method="POST" action="{{ route('shop.carrito.add') }}">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $p->id }}">

                        <label class="form-label fw-semibold">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control mb-3"
                               value="1" min="1" max="{{ $p->existencias }}" style="width:120px">

                        <button class="btn btn-cart px-4">
                            Agregar al carrito
                        </button>
                    </form>
                @else
                    <div class="alert alert-secondary">
                        Este producto no tiene existencias.
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
