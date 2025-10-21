@extends('layouts.app')

@section('content')
    <style>
        /* ==================== NAV CENTRADO  ==================== */
        /* ==== NAV layout con grid ==== */
        .navbar .row{ --bs-gutter-x: 1rem; }
        .nav-search .search-wrap{ max-width: 720px; margin-inline: auto; }
        @media (max-width: 991.98px){
            .nav-search .search-wrap{ max-width: 100%; }
        }

        /* Altura compacta del input/botón en el navbar */
        .navbar .search-wrap input,
        .navbar .search-wrap .btn{ height: 40px; }

        /* Botón carrito */
        .cart-btn{
            display:inline-flex; align-items:center; gap:.5rem;
            padding:.4rem .6rem; border:1px solid rgba(255,255,255,.35);
            border-radius:12px; color:#fff; background:transparent;
        }
        .cart-btn:hover{ background:rgba(255,255,255,.08); color:#fff; }
        .badge-cart{
            position: absolute; top:-6px; right:-6px;
            font-size:.70rem; border-radius:999px; padding:.15rem .38rem;
            background:var(--orange-600); color:#fff;
        }
        /* Ajuste visual de enlaces en la franja oscura */
        .navbar-orange .nav-link{ color:#eaeaea!important; }


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

        /* ===== Base global (como tu estructura) ===== */
        html, body{
            font-family: var(--font-body);
            background-color: #fff;
            color:#222;
        }
        .content{
            padding:40px; background:#fff; border-radius:20px;
            box-shadow:0 0 10px rgba(0,0,0,.1); text-align:center;
        }
        .content h2{
            font-family: var(--font-display);
            color:#f86a04; text-shadow:1px 1px 0 #000; margin-bottom:30px;
        }
        .stats-grid{display:flex; flex-wrap:wrap; gap:30px; justify-content:center;}
        .card-stat{
            background:linear-gradient(to bottom,#ff5912,#9c3a0f); color:#fff; border-radius:20px;
            padding:30px; width:240px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,.2);
            transition:transform .3s;
        }
        .card-stat:hover{ transform:scale(1.05); }
        .card-stat i{ font-size:40px; margin-bottom:15px; text-shadow:1px 1px 2px #000; }
        .card-stat h3{
            font-family: var(--font-display);
            font-size:22px; margin-bottom:8px; text-shadow:1px 1px 2px #000;
        }
        .card-stat p{ font-size:28px; font-weight:700; margin:0; }

        /* ========= TIPOGRAFÍA EN SECCIONES ========= */
        h1,h2,h3,.navbar-brand,.section-title,.hero-title{ font-family: var(--font-display); letter-spacing:.5px; }
        .bebas{ font-family: var(--font-display); letter-spacing:.6px; }

        /* ========= NAVBAR ========= */
        .navbar-orange{background:linear-gradient(90deg, var(--dark), #1a1a1c);}
        .navbar-brand{letter-spacing:1px; font-weight:700; color:#fff !important;}
        .nav-link{color:#eaeaea!important; opacity:.9}
        .nav-link:hover{opacity:1}
        .navbar-toggler{border-color:rgba(255,255,255,.35)}
        .navbar-toggler-icon{filter:invert(1);}

        /* ========= HERO ========= */
        .hero{
            position:relative; min-height:72vh; display:grid; place-items:center; overflow:hidden;
            background: radial-gradient(1200px 600px at 10% 10%, var(--orange-500) 0%, var(--orange-700) 40%, var(--dark) 75%);
            color:#fff;
        }
        .hero .badge{background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25)}
        .hero-title{font-weight:800; line-height:1.05;}
        .hero-title span{ color:var(--orange-300); }
        .hero-sub{opacity:.95}
        .hero-cta .btn{padding:.9rem 1.25rem; font-weight:600; border-radius:.75rem}
        .btn-contrast{background:#fff; color:var(--dark);}
        .btn-contrast:hover{background:#f4f4f6;}
        .btn-ghost{border:1px solid rgba(255,255,255,.7); color:#fff;}
        .btn-ghost:hover{background:rgba(255,255,255,.1);}



        /* ========= SECCIONES ========= */
        .section{padding:70px 0}
        .section-title{font-weight:800;}
        .text-orange{color:var(--orange-700)}
        .chip{display:inline-block;padding:.4rem .7rem;border-radius:999px;background:rgba(255,138,48,.12);color:var(--orange-700);font-weight:600;font-size:1rem}

        /* ========= CARDS ========= */
        .card-modern{border:0; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.06); overflow:hidden; background:var(--card)}
        .card-modern .thumb{height:190px;background:#eee; background-size:cover;background-position:center;}
        .card-modern .price{color:var(--orange-700); font-weight:800}
        .card-modern:hover{transform:translateY(-2px); box-shadow:0 14px 34px rgba(0,0,0,.08); transition:all .25s ease}

        /* ========= CATEGORÍAS ========= */
        .cat-pill{display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .9rem; border-radius:999px; background:#fff; border:1px solid #eee}

        /* ========= CTA BANDA ========= */
        .band-cta{background: linear-gradient(90deg, var(--orange-700), var(--orange-500)); color:#fff;border-radius:18px}

        /* ========= FOOTER ========= */
        footer{background:#0f0f10;color:#c9c9ce}
        footer a{color:#ffb347;text-decoration:none}
        footer a:hover{color:#fff}

        /* ========= BUSCADOR ========= */
        .search-wrap{background:#fff;border-radius:16px; padding:.6rem; box-shadow:0 10px 28px rgba(0,0,0,.08)}
        .search-wrap input{border:0; font-family:var(--font-body);}
        .search-wrap .btn{border-radius:12px; font-weight:600;background:var(--orange-700); border:0}
        .search-wrap .btn:hover{background:var(--orange-600)}

        /* ========= UTILIDADES ========= */
        .divider{height:1px;background:linear-gradient(90deg,transparent,#eaeaea,transparent)}
        .object-fit-cover{object-fit:cover;}
    </style>

    <!-- ==================== NAV ==================== -->
    <nav class="navbar navbar-expand-lg navbar-orange sticky-top">
        <div class="container">
            <div class="row align-items-center w-100 g-2">

                <!-- Izquierda: Marca -->
                <div class="col-6 col-lg-3 order-1">
                    <a class="navbar-brand" href="#">FERRETERIA HERNÁNDEZ</a>
                </div>

                <!-- Toggler (móvil) + Carrito visible en móvil -->
                <div class="col-6 d-lg-none order-2 d-flex justify-content-end align-items-center gap-2">
                    <!-- Carrito (abre modal) -->
                    <button class="position-relative cart-btn" type="button"
                            data-bs-toggle="modal" data-bs-target="#cartModal" aria-label="Abrir carrito">
                        <!-- Ícono carrito (SVG inline) -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="badge-cart">0</span>
                    </button>

                    <!-- Hamburguesa -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-label="Menú">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Centro: Búsqueda (full en móvil, centrada en desktop) -->
                <div class="col-12 col-lg-6 order-4 order-lg-2 nav-search">
                    <form class="search-wrap d-flex align-items-center gap-2" action="#" method="GET">
                        <input type="search" name="q" class="form-control" placeholder="Buscar (ej. taladro, brocas, pintura)">
                        <button class="btn btn-primary px-3" type="submit">Buscar</button>
                    </form>
                </div>

                <!-- Derecha: Menú + Carrito + CTA (desktop) -->
                <div class="col-12 col-lg-3 order-3 order-lg-3">
                    <div id="navMain" class="collapse navbar-collapse justify-content-lg-end">
                        <ul class="navbar-nav align-items-lg-center gap-lg-3 ms-lg-3">
                            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>

                            <!-- Carrito (desktop) -->
                            <li class="nav-item d-none d-lg-inline">
                                <button class="position-relative cart-btn ms-lg-2" type="button"
                                        data-bs-toggle="modal" data-bs-target="#cartModal" aria-label="Abrir carrito">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span class="badge-cart">0</span>
                                </button>
                            </li>

                            <!-- CTA -->
                            <li class="nav-item d-none d-lg-inline">
                                <a class="btn btn-sm text-white"
                                   style="background:linear-gradient(90deg,var(--orange-600),var(--orange-300)); border:0"
                                   href="#catalogo">Ver catálogo</a>
                            </li>
                        </ul>

                        <!-- Búsqueda y CTA en móvil dentro del menú (por si quieres también aquí) -->
                        <div class="d-lg-none mt-3">
                            <a class="btn w-100 text-white"
                               style="background:linear-gradient(90deg,var(--orange-600),var(--orange-300)); border:0"
                               href="#catalogo">Ver catálogo</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    </nav>



    <!-- ==================== HERO ==================== -->
    <header class="hero">
        <div class="container py-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="badge rounded-pill mb-3">NUEVO · Catálogo en línea</span>
                    <h1 class="hero-title display-4 mb-3">HERRAMIENTAS Y FERRETERÍA <span>AL INSTANTE</span></h1>
                    <p class="hero-sub mb-4">Compra online con entrega rápida. Calidad, precio justo y stock actualizado.</p>

                    <div class="hero-cta d-flex gap-2">
                        <a class="btn btn-contrast" href="#productos">Explorar productos</a>
                        <a class="btn btn-ghost" href="#contacto">Hablar con ventas</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="ratio-16x9 rounded-4 overflow-hidden img-fade">
                            <img src="{{ asset('img/hero.jpg') }}" class="w-100 h-100" style="object-fit:contain;" alt="Logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ==================== HIGHLIGHTS ==================== -->
    <section class="section">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card card-modern p-4 h-100">
                        <div class="chip mb-2">Entrega 24-48h</div>
                        <h5 class="fw-bold mb-2">Rápido y confiable</h5>
                        <p class="text-muted mb-0">Envíos locales con seguimiento y retiro en tienda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-modern p-4 h-100">
                        <div class="chip mb-2">Mejor precio</div>
                        <h5 class="fw-bold mb-2">Ofertas semanales</h5>
                        <p class="text-muted mb-0">Promociones activas y descuentos por volumen.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-modern p-4 h-100">
                        <div class="chip mb-2">Soporte experto</div>
                        <h5 class="fw-bold mb-2">Asesoría técnica</h5>
                        <p class="text-muted mb-0">Te ayudamos a elegir la herramienta adecuada.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr class="divider">

    <!-- ==================== CATEGORÍAS ==================== -->
    <section id="categorias" class="section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title mb-0">Categorías destacadas</h2>
                <a class="text-orange fw-semibold" href="#catalogo">Ver todo →</a>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="cat-pill">Herramientas eléctricas</span>
                <span class="cat-pill">Manuales</span>
                <span class="cat-pill">Pinturas</span>
                <span class="cat-pill">Plomería</span>
                <span class="cat-pill">Seguridad</span>
                <span class="cat-pill">Ferretería general</span>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-modern">
                        <div class="thumb" style="background-image:url('/img/cat-herramientas.jpg')"></div>
                        <div class="p-3">
                            <h5 class="fw-bold mb-1">Herramientas eléctricas</h5>
                            <p class="text-muted mb-2">Taladros, esmeriles, sierras y más.</p>
                            <a class="text-orange fw-semibold" href="#catalogo">Ver productos →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-modern">
                        <div class="thumb" style="background-image:url('/img/cat-pinturas.jpg')"></div>
                        <div class="p-3">
                            <h5 class="fw-bold mb-1">Pinturas y acabados</h5>
                            <p class="text-muted mb-2">Esmaltes, vinílicas, rodillos y brochas.</p>
                            <a class="text-orange fw-semibold" href="#catalogo">Ver productos →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-modern">
                        <div class="thumb" style="background-image:url('/img/cat-plomeria.jpg')"></div>
                        <div class="p-3">
                            <h5 class="fw-bold mb-1">Plomería</h5>
                            <p class="text-muted mb-2">Tubos, conexiones, válvulas y más.</p>
                            <a class="text-orange fw-semibold" href="#catalogo">Ver productos →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== PRODUCTOS DESTACADOS ==================== -->
    <section id="productos" class="section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title mb-0">Productos destacados</h2>
                <a class="text-orange fw-semibold" href="#catalogo">Ver catálogo completo →</a>
            </div>

            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card-modern h-100">
                        <div class="thumb" style="background-image:url('/img/prod-taladro.jpg')"></div>
                        <div class="p-3">
                            <h6 class="text-muted mb-1">Truper</h6>
                            <h5 class="fw-bold">Taladro 500W</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">$ 899.00</span>
                                <a class="text-orange fw-semibold" href="#detalle">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card-modern h-100">
                        <div class="thumb" style="background-image:url('/img/prod-esmeril.jpg')"></div>
                        <div class="p-3">
                            <h6 class="text-muted mb-1">Pretul</h6>
                            <h5 class="fw-bold">Esmeriladora 4 1/2"</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">$ 749.00</span>
                                <a class="text-orange fw-semibold" href="#detalle">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card-modern h-100">
                        <div class="thumb" style="background-image:url('/img/prod-martillo.jpg')"></div>
                        <div class="p-3">
                            <h6 class="text-muted mb-1">Stanley</h6>
                            <h5 class="fw-bold">Martillo carpintero</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">$ 199.00</span>
                                <a class="text-orange fw-semibold" href="#detalle">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card-modern h-100">
                        <div class="thumb" style="background-image:url('/img/prod-brocas.jpg')"></div>
                        <div class="p-3">
                            <h6 class="text-muted mb-1">Bosch</h6>
                            <h5 class="fw-bold">Set de brocas</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">$ 259.00</span>
                                <a class="text-orange fw-semibold" href="#detalle">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banda CTA -->
            <div class="band-cta p-4 p-md-5 mt-5">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <h3 class="mb-1 fw-bold">¿Buscas precios por volumen?</h3>
                        <p class="mb-0">Escríbenos y obtén una cotización personalizada para tu obra o negocio.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a class="btn btn-light fw-semibold px-4" href="#contacto">Solicitar cotización</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CONTACTO ==================== -->
    <section id="contacto" class="section">
        <div class="container">
            <h2 class="section-title mb-3">Contacto</h2>
            <p class="text-muted mb-4">Déjanos tu mensaje y te respondemos a la brevedad.</p>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card card-modern p-4">
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" placeholder="Tu nombre">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" placeholder="tunombre@correo.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea class="form-control" rows="4" placeholder="Cuéntanos qué necesitas"></textarea>
                            </div>
                            <button type="submit" class="btn" style="background:var(--orange-700); color:#fff;">Enviar</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-modern p-4 h-100">
                        <h5 class="fw-bold mb-2">Horarios</h5>
                        <p class="mb-4"><strong>Lun–Sáb:</strong> 08:00–20:00 &nbsp; · &nbsp; <strong>Dom:</strong> 09:00–15:00</p>
                        <h5 class="fw-bold mb-2">Tienda</h5>
                        <p class="mb-1">Calle Ejemplo 123, Villa Victoria, EDOMEX</p>
                        <p class="mb-0">Tel: (722) 000 0000</p>
                        <div class="ratio ratio-16x9 mt-3 rounded" style="overflow:hidden;">
                            <img src="/img/mapa.jpg" class="w-100 h-100 object-fit-cover" alt="Mapa">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Carrito -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tu carrito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <!-- Lista de productos (ejemplo estático; reemplaza con tu loop Blade) -->
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="/img/prod-taladro.jpg" class="rounded" width="56" height="56" style="object-fit:cover" alt="Taladro">
                                <div>
                                    <div class="fw-semibold">Taladro 500W</div>
                                    <small class="text-muted">Truper · SKU T500</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <input type="number" class="form-control form-control-sm" value="1" min="1" style="width:72px">
                                <div class="fw-bold">$899.00</div>
                                <button type="button" class="btn btn-sm btn-outline-danger">Quitar</button>
                            </div>
                        </li>

                        <li class="list-group-item py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="/img/prod-brocas.jpg" class="rounded" width="56" height="56" style="object-fit:cover" alt="Set de brocas">
                                <div>
                                    <div class="fw-semibold">Set de brocas</div>
                                    <small class="text-muted">Bosch · SKU B25</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <input type="number" class="form-control form-control-sm" value="2" min="1" style="width:72px">
                                <div class="fw-bold">$259.00</div>
                                <button type="button" class="btn btn-sm btn-outline-danger">Quitar</button>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="modal-footer d-block">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">Envío y descuentos se calculan al finalizar la compra.</div>
                        <div class="fs-5"><strong>Total: </strong>$1,417.00</div>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="#catalogo" class="btn btn-outline-secondary" data-bs-dismiss="modal">Seguir comprando</a>
                        <a href="{{ url('/carrito') }}" class="btn btn-outline-dark">Ir al carrito</a>
                        <a href="{{ url('/checkout') }}" class="btn btn-primary" style="background:var(--orange-700); border:0;">Pagar ahora</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ==================== FOOTER ==================== -->
    <footer class="py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small">© 2025 Ferretería Hernández. Todos los derechos reservados.</p>
            <div class="d-flex gap-3 small">
                <a href="#">Privacidad</a>
                <a href="#">Términos</a>
                <a href="#">Soporte</a>
            </div>
        </div>
    </footer>
@endsection
