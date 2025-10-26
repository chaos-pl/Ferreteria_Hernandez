@extends('layouts.app')

@section('content')
    <style>
        :root{
            --orange-900:#8a3b00; --orange-700:#ff6a00; --orange-600:#ff7a1a;
            --orange-500:#ff8c00; --orange-300:#ffb347; --dark:#0f0f10;
            --muted:#6b7280; --light:#f8f9fb; --card:#ffffff;

            --font-display:'Bebas Neue', sans-serif;
            --font-body:'Archivo', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        html,body{ background:#fff; font-family:var(--font-body); }
        .bebas{ font-family:var(--font-display); letter-spacing:.4px; }

        .dashboard-container{min-height:calc(100vh - 110px); display:flex; gap:20px; padding:20px; background:linear-gradient(180deg,#fff,var(--light));}
        .sidebar{
            width:260px; flex-shrink:0; color:#fff; border-radius:20px; padding:20px;
            background:linear-gradient(180deg,var(--orange-700),var(--orange-500));
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            display:flex; flex-direction:column;
        }

        .sidebar .brand{display:flex;align-items:center;gap:12px;margin-bottom:28px}
        .sidebar .brand img{width:60px;height:60px;border-radius:12px;object-fit:cover;border:2px solid rgba(255,255,255,.9)}
        .sidebar .brand span{font-size:1.7rem;font-weight:800;text-shadow:0 2px 4px rgba(0,0,0,.25)}
        .sidebar a{color:#fff;text-decoration:none;display:flex;align-items:center;gap:10px;padding:12px;border-radius:12px;font-weight:700;transition:.25s}
        .sidebar a:hover{background:rgba(255,255,255,.12);transform:translateX(2px)}
        .sidebar a.active{background:rgba(255,255,255,.18);box-shadow:inset 3px 0 0 #fff}
        .logout{margin-top:auto}

        .content{flex:1;padding:28px;border-radius:20px;background:var(--card);box-shadow:0 10px 30px rgba(0,0,0,.06)}
        .content .title{font-family:var(--font-display);font-weight:800;color:var(--orange-700);letter-spacing:.5px;margin-bottom:16px}
        .content .subtitle{color:var(--muted)}

        @media (max-width:991.98px){ .dashboard-container{flex-direction:column} .sidebar{width:100%} }

        .nav-title{
            font-size:.78rem; letter-spacing:.08em; text-transform:uppercase;
            margin:10px 6px 6px; font-weight:800; opacity:.9;
        }
        .submenu{ display:flex; flex-direction:column; margin:4px 0 12px 34px; }
        .submenu a{ padding:10px; border-radius:10px; font-weight:600; opacity:.95; }
        .submenu a:hover{ background:rgba(255,255,255,.12); transform:none; }
    </style>

    <div class="dashboard-container">
        {{-- Sidebar --}}
        <aside class="sidebar">
            <div class="brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
                <span class="bebas text-shadow-black" style="text-align: center">FERRETERÍA HERNADEZ</span>
            </div>

            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa-solid fa-home"></i><span class="bebas">INICIO</span>
            </a>

            {{-- ================== CATÁLOGO ================== --}}
            <div class="nav-title">Catálogo</div>

            {{-- Categorías --}}
            <a href="{{ route('admin.categorias.index') }}"
               class="{{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i><span class="bebas">CATEGORÍAS</span>
            </a>

            {{-- Submenú SOLO cuando estás en rutas de categorías --}}
            @if(request()->routeIs('admin.categorias.*'))
                <div class="submenu">
                    @php($currentId = request()->route('categoria')?->id)
                    @foreach(($categoriasSidebar ?? []) as $cat)
                        <a href="{{ route('admin.categorias.show', $cat) }}"
                           class="{{ (int)$currentId === (int)$cat->id ? 'active' : '' }}">
                            <i class="fa-solid fa-angle-right"></i><span>{{ $cat->nombre }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Municipios --}}
            <a href="{{ route('admin.municipios.index') }}"
               class="{{ request()->routeIs('admin.municipios.*') ? 'active' : '' }}">
                <i class="fa-solid fa-city"></i><span class="bebas">MUNICIPIOS</span>
            </a>
            {{-- =============================================== --}}

            {{-- Direcciones --}}
            <a href="{{ route('admin.direcciones.index') }}"
               class="{{ request()->routeIs('admin.direcciones.*') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i>
                <span class="bebas">DIRECCIONES</span>
            </a>
            {{-- =============================================== --}}
            <a href="{{ url('/') }}" class="logout">
                <i class="fa-solid fa-arrow-left"></i><span class="bebas">SALIR</span>
            </a>
        </aside>

        {{-- Área de contenido del dashboard --}}
        <section class="content">
            @yield('dashboard-content')
        </section>
    </div>
@endsection
