@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <h2 class="mb-4">Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold">
                        Resumen de productos
                    </div>
                    <div class="card-body">

                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>
                                    <strong>{{ $item->producto->nombre }}</strong><br>
                                    <small class="text-muted">Cantidad: {{ $item->cantidad }}</small>
                                </div>
                                <div class="text-end">
                                    <span>${{ number_format($item->precio_unit,2) }}</span><br>
                                    <small class="text-muted">Subtotal: ${{ number_format($item->cantidad * $item->precio_unit, 2) }}</small>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body">
                        @php
                            $total = $cart->items->sum(fn($i)=>$i->cantidad * $i->precio_unit);
                        @endphp

                        <h5 class="fw-bold">Total a pagar</h5>
                        <p class="display-6">${{ number_format($total, 2) }}</p>

                        <form action="{{ route('shop.checkout.process') }}" method="POST">
                            @csrf
                            <button class="btn btn-success w-100 btn-lg">
                                Confirmar compra
                            </button>
                        </form>

                        <a href="{{ route('shop.carrito') }}" class="btn btn-outline-secondary w-100 mt-3">
                            Volver al carrito
                        </a>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
