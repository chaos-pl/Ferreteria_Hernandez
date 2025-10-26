<?php
@extends('dashboard')
@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Nuevo producto</h3>
        @include('admin.productos._form', ['route' => route('admin.productos.store'), 'method' => 'POST'])
    </div>
@endsection
