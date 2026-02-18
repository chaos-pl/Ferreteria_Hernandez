@extends('layouts.dashboard')

@section('dashboard-content')

    <div class="container py-4">

        <h2 class="bebas" style="letter-spacing:1px;">Dashboard General</h2>
        <p class="text-muted">Resumen de actividad de Ferretería Hernández</p>

        {{-- MÉTRICAS --}}
        <div class="row g-4 mb-4">

            @php
                $cards = [
                    ['Productos', $totalProductos, 'fa-boxes'],
                    ['Stock Bajo', $productosStockBajo, 'fa-triangle-exclamation'],
                    ['Categorías', $categorias, 'fa-tags'],
                    ['Proveedores', $proveedores, 'fa-industry'],
                    ['Clientes', $clientes, 'fa-user'],
                    ['Usuarios Totales', $usuarios, 'fa-users'],
                    ['Ventas del Mes', $ventasMes, 'fa-cart-shopping'],
                    ['Ingresos del Mes', '$'.number_format($ingresosMes,2), 'fa-money-bill-wave'],
                ];
            @endphp

            @foreach ($cards as $c)
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3 text-center" style="border-radius:14px;">
                        <i class="fa-solid {{ $c[2] }} fa-2x mb-2" style="color:#ff6a00"></i>
                        <h6 class="fw-bold">{{ $c[0] }}</h6>
                        <div class="fs-4 bebas">{{ $c[1] }}</div>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- GRAFICAS --}}
        <div class="row g-4">

            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold">Ventas por mes</h5>
                    <canvas id="chartVentas"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold">Ingresos por mes</h5>
                    <canvas id="chartIngresos"></canvas>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ventasLabels = {!! json_encode(array_keys($ventasPorMes->toArray())) !!};
        const ventasData   = {!! json_encode(array_values($ventasPorMes->toArray())) !!};

        const ingresosLabels = {!! json_encode(array_keys($ingresosPorMes->toArray())) !!};
        const ingresosData   = {!! json_encode(array_values($ingresosPorMes->toArray())) !!};

        // Gráfica Ventas
        new Chart(document.getElementById("chartVentas"), {
            type: 'line',
            data: {
                labels: ventasLabels,
                datasets: [{
                    label: 'Ventas',
                    data: ventasData,
                    borderColor: '#ff6a00',
                    backgroundColor: 'rgba(255,106,0,0.2)',
                    tension: 0.3
                }]
            }
        });

        // Gráfica Ingresos
        new Chart(document.getElementById("chartIngresos"), {
            type: 'bar',
            data: {
                labels: ingresosLabels,
                datasets: [{
                    label: 'Ingresos',
                    data: ingresosData,
                    backgroundColor: '#ff8c00'
                }]
            }
        });
    </script>
@endpush
