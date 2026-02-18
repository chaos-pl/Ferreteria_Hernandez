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
        /* VARIABLES DE COLOR Y TIPOGRAFÍA */
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

        /* ========= NAVBAR GENERAL ========= */
        .navbar-orange{
            background:linear-gradient(90deg, var(--dark), #1a1a1c);
        }
        .navbar-brand{
            font-family:var(--font-display);
            letter-spacing:1px;
            font-weight:700;
            color:#fff !important;
        }
        .nav-link{
            color:#eaeaea!important;
            opacity:.9;
            font-weight:500;
        }
        .nav-link:hover{
            opacity:1;
        }

        /* Ícono hamburguesa */
        .navbar-toggler{
            border-color:rgba(255,255,255,.35);
        }
        .navbar-toggler-icon{
            filter:invert(1);
        }

        /* ===================== BUSCADOR ===================== */
        .nav-search .search-wrap{
            background:#fff;
            border-radius:16px;
            padding:.6rem;
            box-shadow:0 10px 28px rgba(0,0,0,.08);
            max-width:720px;
            margin-inline:auto;
        }
        .nav-search input{
            border:0;
            font-family:var(--font-body);
        }
        .nav-search .btn{
            height:40px;
            border-radius:12px;
            font-weight:600;
            background:var(--orange-700);
            border:0;
        }
        .nav-search .btn:hover{
            background:var(--orange-600);
        }

        /* En pantallas móviles ocupa el 100% */
        @media (max-width: 991.98px){
            .nav-search .search-wrap{
                max-width:100%;
            }
        }

        /* ===================== CARRITO ===================== */
        .cart-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:.5rem;
            padding:.4rem .6rem;
            border:1px solid rgba(255,255,255,.35);
            border-radius:12px;
            color:#fff;
            background:transparent;
            position:relative;
        }
        .cart-btn:hover{
            background:rgba(255,255,255,.08);
            color:#fff;
        }
        .badge-cart{
            position:absolute;
            top:-6px;
            right:-6px;
            font-size:.70rem;
            border-radius:999px;
            padding:.15rem .38rem;
            background:var(--orange-600);
            color:#fff;
        }

        /* Ajuste preventivo para que los dropdown no se corten */
        .navbar{
            z-index:1035;
        }
        .navbar .container,
        .navbar .row,
        .navbar [class*="col-"]{
            overflow:visible;
        }
        .dropdown-menu{
            z-index:1080;
        }

        /* QUITAR BORDES DEL INPUT DENTRO DEL BUSCADOR */
        .search-wrap input:focus{
            box-shadow:none !important;
            outline:0 !important;
        }

        /* Alineación óptima del contenido */
        .navbar .row{
            --bs-gutter-x:1rem;
        }
    </style>

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
    {{-- ================= NAVBAR UNIFICADO ================= --}}
    <nav class="navbar navbar-expand-lg navbar-orange sticky-top">
        <div class="container">
            <div class="row align-items-center w-100 g-2">

                {{-- Izquierda: Marca --}}
                <div class="col-6 col-lg-3 order-1">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        FERRETERÍA HERNÁNDEZ
                    </a>
                </div>

                {{-- Móvil: Carrito + Hamburguesa --}}
                <div class="col-6 d-lg-none order-2 d-flex justify-content-end align-items-center gap-2">

                    {{-- Botón carrito --}}
                    <button class="position-relative cart-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#cartModal">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="badge-cart">{{ $cartCount ?? 0 }}</span>
                    </button>

                    {{-- Botón menú --}}
                    <button class="navbar-toggler" type="button"
                            data-bs-toggle="collapse" data-bs-target="#navMain">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                {{-- Centro: Barra de búsqueda --}}
                <div class="col-12 col-lg-6 order-4 order-lg-2 nav-search">
                    <form class="search-wrap d-flex align-items-center gap-2" method="GET" action="{{ route('shop.catalogo') }}">
                        <input type="search" name="q" class="form-control"
                               placeholder="Buscar productos (ej. taladro, brocas, pintura)...">
                        <button class="btn btn-primary px-3" type="submit">Buscar</button>
                    </form>
                </div>

                {{-- Derecha --}}
                <div class="col-12 col-lg-3 order-3 order-lg-3">
                    <div id="navMain" class="collapse navbar-collapse justify-content-lg-end">

                        <ul class="navbar-nav align-items-lg-center gap-lg-3 ms-lg-2">

                            <li class="nav-item">
                                <a class="nav-link" href="#contacto">Contacto</a>
                            </li>

                            {{-- Si es cliente, mostrar catálogo --}}
                            @auth
                                @role('cliente')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('shop.catalogo') }}">Catálogo</a>
                                </li>
                                @endrole
                            @endauth

                            {{-- Carrito desktop --}}
                            <li class="nav-item d-none d-lg-inline">
                                <button class="position-relative cart-btn" data-bs-toggle="modal" data-bs-target="#cartModal">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="badge-cart">{{ $cartCount ?? 0 }}</span>
                                </button>
                            </li>

                            {{-- Login / Usuario --}}
                            {{-- Login / Usuario --}}
                            @guest
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                            @else
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end shadow">

                                        {{-- Mostrar Dashboard solo si NO es cliente --}}
                                        @unless(auth()->user()->hasRole('cliente'))
                                            <li><a class="dropdown-item" href="{{ route('home') }}">Dashboard</a></li>
                                            <li><hr></li>
                                        @endunless

                                        {{-- Logout --}}
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Cerrar sesión
                                            </a>
                                        </li>
                                    </ul>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endguest


                        </ul>

                    </div>
                </div>

            </div>
        </div>
    </nav>

    {{-- Asegura que el modal esté disponible --}}
    @include('shop.partials.modal-carrito')


    <main class="py">
        @yield('content')
    </main>

    @stack('scripts')
</div>
</body>
</html>
