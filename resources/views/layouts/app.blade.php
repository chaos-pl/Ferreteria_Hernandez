<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Ferretería Hernández'))</title>

    {{-- Fuentes locales --}}
    <style>
        @font-face{
            font-family:'Bebas Neue';
            src:url('{{ asset('fonts/bebasneue/BebasNeue-Regular.ttf') }}') format('truetype');
            font-weight:400; font-style:normal; font-display:swap;
        }
        @font-face{
            font-family:'Archivo';
            src:url('{{ asset('fonts/archivo/Archivo-Regular.ttf') }}') format('truetype');
            font-weight:400; font-style:normal; font-display:swap;
        }

        /* ===== Paleta (del landing) ===== */
        :root{
            --orange-900:#8a3b00;
            --orange-700:#ff6a00;
            --orange-600:#ff7a1a;
            --orange-500:#ff8c00;
            --orange-300:#ffb347;
            --dark:#0f0f10;
            --muted:#6b7280;
            --light:#f8f9fb;
            --card:#ffffff;

            --font-display:'Bebas Neue', sans-serif;
            --font-body:'Archivo', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        html, body{ font-family:var(--font-body); background:#fff; color:#111; }
        .bebas{ font-family:var(--font-display); letter-spacing:.5px; }

        /* ===== NAV estilo landing ===== */
        .navbar-orange{ background:linear-gradient(90deg, var(--dark), #1a1a1c); }
        .navbar-orange .navbar-brand,
        .navbar-orange .nav-link{ color:#eaeaea!important; }
        .navbar-orange .nav-link{ opacity:.9; }
        .navbar-orange .nav-link:hover{ opacity:1; }
        .navbar-brand{ font-family:var(--font-display); font-weight:700; letter-spacing:.6px; }

        /* Frase central */
        .slogan-1{ color:var(--orange-700); font-weight:800; text-transform:uppercase; }
        .slogan-2{ color:#cfd2d6; }

        /* Botón Inicio con la paleta */
        .btn-home{
            color:#fff; border:0;
            background:linear-gradient(90deg,var(--orange-600),var(--orange-300));
            padding:.45rem .8rem; border-radius:.6rem;
        }
        .btn-home:hover{ filter:brightness(.95); }

        /* Ajustes menores */
        .navbar-toggler{ border-color:rgba(255,255,255,.35) }
        .navbar-toggler-icon{ filter:invert(1); }
        /* Asegura que el dropdown pueda salir del navbar y quede por encima */
        .navbar{ z-index: 1040; }
        .navbar .container,
        .navbar .container-fluid,
        .navbar .row,
        .navbar [class*="col-"]{ overflow: visible; }   /* clave para que no lo recorten */
        .dropdown-menu{ z-index: 1080; }                 /* sobre la hero */

    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
<div id="app">

    {{-- NAV --}}
    <nav class="navbar navbar-expand-lg navbar-orange shadow-sm">
        <div class="container-fluid">
            <div class="row w-100 align-items-center g-2">

                {{-- Col 1: Logos + Nombre --}}
                <div class="col-6 col-md-4 d-flex align-items-center">
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                        {{-- Logo(s). Usa los que tengas en /public/img/ --}}
                        @if(file_exists(public_path('img/logo.png')))
                            <img src="{{ asset('img/logo.png') }}" alt="Logo" height="56" class="me-2">
                        @endif
                        @if(file_exists(public_path('img/logo_sec.png')))
                            <img src="{{ asset('img/logo_sec.png') }}" alt="Logo 2" height="44" class="me-2 d-none d-md-inline">
                        @endif
                        <span>FERRETERÍA HERNÁNDEZ</span>
                    </a>
                </div>

                {{-- Col 2: Frase ferretera al centro (md+) --}}
                <div class="col-md-4 text-center d-none d-md-block">
                    <div class="bebas slogan-1" style="font-size:1.05rem;">
                        LAS MEJORES OFERTAS EN HERRAMIENTAS Y FERRETERÍA
                    </div>
                    <div class="slogan-2" style="font-size:.95rem;">
                        Todo para tu obra y hogar · calidad profesional y precio justo
                    </div>
                </div>

                {{-- Col 3: Botones de acceso / usuario --}}
                <div class="col-6 col-md-4 d-flex justify-content-end align-items-center">
                    <ul class="navbar-nav flex-row align-items-center gap-2">
                        {{-- Botón Inicio (siempre visible) --}}
                        <li class="nav-item">
                            <a class="btn-home text-decoration-none" href="{{ url('/') }}">Inicio</a>
                        </li>

                        @guest
                            @if(Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
                            @endif
                            @if(Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown position-static">
                                <a id="navbarDropdown"
                                   class="nav-link dropdown-toggle"
                                   href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    @if (Route::has('home'))
                                        <li><a class="dropdown-item" href="{{ route('home') }}">Dashboard</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                    {{-- Toggler solo visible en móvil --}}
                    <button class="navbar-toggler ms-2 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse" aria-label="Menú">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    @stack('scripts')
</div>
</body>
</html>
