@php
    // Asegura compatibilidad con miniCart o cartMini
    $mc = $miniCart ?? $cartMini ?? null;

    // Si no existe carrito abierto, crea uno vacío pero sin romper el modal
    $items = $mc?->items ?? collect();
    $total = $items->sum(fn($i) => $i->cantidad * $i->precio_unit);
@endphp

<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <!-- HEADER -->
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Tu carrito
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($items->count() == 0)
                    <div class="text-center py-4 text-muted">
                        <i class="fa-regular fa-face-frown fa-2xl mb-3"></i>
                        <p class="mb-0">Tu carrito está vacío.</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush">

                        @foreach($items as $it)
                            <li class="list-group-item py-3">

                                <div class="row align-items-center g-3">

                                    <!-- Imagen -->
                                    <div class="col-3 col-md-2">
                                        <img src="{{ $it->producto->imagen_url ?? asset('img/no-img.png') }}"
                                             class="rounded"
                                             style="width:100%; height:70px; object-fit:cover;">
                                    </div>

                                    <!-- Información producto -->
                                    <div class="col-9 col-md-4">
                                        <div class="fw-semibold">{{ $it->producto->nombre }}</div>
                                        <small class="text-muted">
                                            Marca: {{ $it->producto->marca?->nombre ?? 'N/A' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">ID: {{ $it->producto->id }}</small>
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="col-md-3 d-flex">
                                        <form method="POST"
                                              action="{{ route('shop.carrito.update', $it) }}"
                                              class="d-flex w-100 gap-2">
                                            @csrf @method('PATCH')
                                            <input type="number"
                                                   name="cantidad"
                                                   min="1"
                                                   value="{{ $it->cantidad }}"
                                                   class="form-control form-control-sm text-center">
                                            <button class="btn btn-outline-secondary btn-sm">OK</button>
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-md-2 fw-bold text-end">
                                        ${{ number_format($it->subtotal, 2) }}
                                    </div>

                                    <!-- Quitar -->
                                    <div class="col-md-1 text-end">
                                        <form method="POST"
                                              action="{{ route('shop.carrito.remove', $it) }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </li>
                        @endforeach

                    </ul>
                @endif

            </div>

            <!-- FOOTER -->
            <div class="modal-footer flex-column">

                <div class="d-flex w-100 justify-content-between align-items-center mb-3">
                    <span class="text-muted">El envío y descuentos se calculan al pagar.</span>
                    <span class="fs-4 fw-bold">Total: ${{ number_format($total, 2) }}</span>
                </div>

                <div class="d-flex w-100 justify-content-end gap-2">

                    <button class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Seguir comprando
                    </button>

                    <a href="{{ route('shop.carrito') }}"
                       class="btn btn-outline-dark">
                        Ver carrito
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary"
                           style="background:#ff7a1a; border:0;">
                            Inicia sesión para pagar
                        </a>
                    @else
                        <form method="POST" action="{{ route('shop.carrito.checkout') }}">
                            @csrf
                            <button class="btn btn-primary fw-semibold"
                                    style="background:#ff6a00; border:0;">
                                Finalizar compra
                            </button>
                        </form>
                    @endguest

                </div>

            </div>

        </div>
    </div>
</div>
