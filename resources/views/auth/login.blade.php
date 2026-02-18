@extends('layouts.app')

@section('content')
    <style>
        .login-wrapper{
            min-height: 85vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ff6a00 0%, #ff8c00 40%, #0f0f10 100%);
            padding: 40px 15px;
        }

        .login-card{
            width: 100%;
            max-width: 460px;
            background:#ffffff;
            border-radius: 18px;
            padding: 35px 40px;
            box-shadow: 0 10px 35px rgba(0,0,0,.25);
            animation: fadeIn .6s ease-out;
        }

        .login-card h3{
            font-family:'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            color:#0f0f10;
            text-align: center;
            margin-bottom: 5px;
        }

        .login-sub{
            text-align:center;
            color:#6b7280;
            margin-bottom: 25px;
            font-size:.95rem;
        }

        .btn-orange{
            background:#ff6a00;
            border:none;
            color:#fff;
            font-weight:bold;
            padding:.7rem 1rem;
            border-radius:10px;
            width:100%;
            font-size:1.05rem;
        }

        .btn-orange:hover{
            background:#ff7a1a;
        }

        .input-custom{
            border-radius:12px;
            padding: .65rem .9rem;
            border:1px solid #d1d5db;
        }

        .input-custom:focus{
            border-color:#ff6a00;
            box-shadow:0 0 0 2px rgba(255,106,0,.25);
        }

        .link-orange{
            color:#ff6a00;
            font-weight:600;
            text-decoration:none;
        }
        .link-orange:hover{
            text-decoration:underline;
        }

        @keyframes fadeIn{
            from { opacity:0; transform:translateY(20px); }
            to { opacity:1; transform:translateY(0); }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">

            <h3>Bienvenido</h3>
            <p class="login-sub">Ingresa tus credenciales para continuar</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="fw-semibold mb-1">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                           class="form-control input-custom @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus>

                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CONTRASEÑA --}}
                <div class="mb-3">
                    <label class="fw-semibold mb-1">Contraseña</label>
                    <input type="password" id="password" name="password"
                           class="form-control input-custom @error('password') is-invalid @enderror"
                           required>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- RECORDAR --}}
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Recuérdame</label>
                </div>

                {{-- BOTÓN --}}
                <button type="submit" class="btn-orange mb-3">Entrar</button>

                {{-- OLVIDÉ CONTRASEÑA --}}
                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-orange">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

            </form>

        </div>
    </div>

@endsection
