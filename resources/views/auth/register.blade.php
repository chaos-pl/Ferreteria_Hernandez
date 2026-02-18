@extends('layouts.app')

@section('content')

    <style>
        .register-wrapper{
            min-height: 90vh;
            display:flex;
            justify-content:center;
            align-items:flex-start;
            padding:50px 20px;
            background: linear-gradient(135deg, #ff6a00 0%, #ff8c00 40%, #0f0f10 100%);
        }

        .register-card{
            width:100%;
            max-width:1000px;
            background:#fff;
            padding:40px 45px;
            border-radius:22px;
            box-shadow:0 12px 35px rgba(0,0,0,.25);
            animation:fadeIn .6s ease-out;
        }

        .register-title{
            font-family:'Bebas Neue', sans-serif;
            font-size:2.6rem;
            text-align:center;
            margin-bottom:10px;
            color:#0f0f10;
        }

        .register-sub{
            text-align:center;
            color:#6b7280;
            margin-bottom:35px;
        }

        .section-title{
            font-family:'Bebas Neue', sans-serif;
            font-size:1.8rem;
            color:#ff6a00;
            margin-bottom:10px;
            margin-top:20px;
        }

        .input-custom{
            border-radius:12px;
            padding:.65rem .9rem;
            border:1px solid #d1d5db;
        }
        .input-custom:focus{
            border-color:#ff6a00;
            box-shadow:0 0 0 2px rgba(255,106,0,.25);
        }

        .btn-orange{
            background:#ff6a00;
            border:none;
            color:#fff;
            font-weight:bold;
            padding:.75rem 1rem;
            border-radius:10px;
            min-width:200px;
            font-size:1.1rem;
        }
        .btn-orange:hover{
            background:#ff7a1a;
        }

        .btn-cancel{
            background:#b30000;
            border:none;
            color:#fff;
            font-weight:bold;
            padding:.75rem 1rem;
            border-radius:10px;
            min-width:200px;
            font-size:1.1rem;
        }

        @keyframes fadeIn{
            from { opacity:0; transform:translateY(20px); }
            to { opacity:1; transform:translateY(0); }
        }
    </style>

    <div class="register-wrapper">
        <div class="register-card">

            <h2 class="register-title">Crear una cuenta</h2>
            <p class="register-sub">Completa la información para registrarte</p>

            <form method="POST" action="{{ route('register') }}" class="row g-4">
                @csrf

                <!-- DATOS DE CUENTA -->
                <h3 class="section-title">Datos de Cuenta</h3>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nombre de usuario *</label>
                    <input type="text" name="username" class="form-control input-custom"
                           required minlength="3" maxlength="20" value="{{ old('username') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control input-custom"
                           required value="{{ old('email') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Contraseña *</label>
                    <input type="password" name="password" class="form-control input-custom"
                           required minlength="8" placeholder="Mínimo 8 dígitos">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Confirmar contraseña *</label>
                    <input type="password" name="password_confirmation" class="form-control input-custom"
                           required minlength="8" placeholder="Mínimo 8 dígitos">
                </div>


                <!-- DATOS PERSONALES -->
                <h3 class="section-title">Datos Personales</h3>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nombre(s) *</label>
                    <input type="text" name="nombre" class="form-control input-custom" required maxlength="120">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Apellido paterno *</label>
                    <input type="text" name="ap" class="form-control input-custom" required maxlength="120">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Apellido materno *</label>
                    <input type="text" name="am" class="form-control input-custom" required maxlength="150">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control input-custom"
                           required pattern="[0-9]{10}" maxlength="10" placeholder="10 dígitos">
                </div>


                <!-- DIRECCIÓN -->
                <h3 class="section-title">Dirección</h3>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Calle *</label>
                    <input type="text" name="calle" class="form-control input-custom" required maxlength="100">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Colonia *</label>
                    <input type="text" name="colonia" class="form-control input-custom" required maxlength="100">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Municipio *</label>
                    <select name="municipios_id" class="form-select input-custom" required>
                        <option value="">Seleccione...</option>
                        @foreach($municipios as $m)
                            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- BOTONES -->
                <div class="col-12 text-center mt-4 mb-3 d-flex justify-content-center gap-3 flex-wrap">
                    <button type="submit" class="btn-orange">
                        <i class="fa-solid fa-user-plus"></i> Registrarse
                    </button>

                    <a href="{{ url('/') }}" class="btn-cancel">
                        <i class="fa-solid fa-arrow-left"></i> Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection
