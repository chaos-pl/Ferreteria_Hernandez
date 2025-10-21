{{-- resources/views/home.blade.php --}}
@extends('layouts.dashboard')

@section('dashboard-content')
    <h2 class="title">Dashboard</h2>
    <p class="subtitle">Resumen general de tu ferretería</p>

    {{-- Puedes pegar aquí tu card anterior o tus widgets --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bebas" style="letter-spacing:.4px;">Dashboard</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            ¡Has iniciado sesión!
        </div>
    </div>
@endsection
