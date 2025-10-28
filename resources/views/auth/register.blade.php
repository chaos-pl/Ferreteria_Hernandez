@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registro</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Nombre de usuario (users.name) --}}
                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">Nombre de usuario</label>
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                           name="username" value="{{ old('username') }}" required autofocus>
                                    @error('username') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="form-group row mt-3">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Dirección de correo electronico</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="form-group row mt-3">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" required>
                                    @error('password') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            {{-- Confirm --}}
                            <div class="form-group row mt-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar contraseña</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                </div>
                            </div>

                            {{-- (Opcional) Datos Persona --}}
                            <hr class="my-4">
                            <h5 class="text-center mb-3">Datos Personales</h5>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Nombre(s)</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}">
                                    @error('nombre') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <label class="col-md-4 col-form-label text-md-right">Apellido paterno</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('ap') is-invalid @enderror" name="ap" value="{{ old('ap') }}">
                                    @error('ap') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <label class="col-md-4 col-form-label text-md-right">Apellido materno</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('am') is-invalid @enderror" name="am" value="{{ old('am') }}">
                                    @error('am') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <label class="col-md-4 col-form-label text-md-right">Teléfono</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}">
                                    @error('telefono') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Registrarse</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
