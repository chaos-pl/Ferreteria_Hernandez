@extends('layouts.app')
@section('content')
    <div class="container py-4">

        <!-- ALERTA (para verificar estilos) -->
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <span class="me-2">✅</span>
            <div>Bootstrap está cargado si ves este recuadro verde con icono.</div>
        </div>

        <!-- HERO -->
        <div class="p-5 mb-4 bg-body-tertiary rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Ferretería Hernández</h1>
                <p class="col-md-8 fs-5">Herramientas y materiales de calidad para tus proyectos.</p>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-lg" href="#destacados">Ver destacados</a>
                    <a class="btn btn-outline-secondary btn-lg" href="#categorias">Categorías</a>
                </div>
            </div>
        </div>

        <!-- BOTONES / BADGES -->
        <div class="mb-4">
            <div class="btn-group me-2" role="group">
                <button type="button" class="btn btn-outline-primary">Inicio</button>
                <button type="button" class="btn btn-outline-primary">Productos</button>
                <button type="button" class="btn btn-outline-primary">Contacto</button>
            </div>
            <span class="badge text-bg-warning">Envíos a todo MX</span>
            <span class="badge text-bg-info">Soporte</span>
        </div>

        <!-- CATEGORÍAS -->
        <section id="categorias" class="mb-5">
            <h2 class="h4 mb-3">Categorías</h2>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/herramientas/600/400" class="card-img-top" alt="Herramientas">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Herramientas</h5>
                            <p class="card-text small text-muted">Manual y eléctrica</p>
                            <a class="btn btn-sm btn-primary" href="#">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/electricidad/600/400" class="card-img-top" alt="Electricidad">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Electricidad</h5>
                            <p class="card-text small text-muted">Cables y luminarias</p>
                            <a class="btn btn-sm btn-primary" href="#">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/plomeria/600/400" class="card-img-top" alt="Plomería">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Plomería</h5>
                            <p class="card-text small text-muted">Tubería y conexiones</p>
                            <a class="btn btn-sm btn-primary" href="#">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/pinturas/600/400" class="card-img-top" alt="Pinturas">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Pinturas</h5>
                            <p class="card-text small text-muted">Esmaltes y accesorios</p>
                            <a class="btn btn-sm btn-primary" href="#">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRODUCTOS DESTACADOS (estático para probar) -->
        <section id="destacados" class="mb-5">
            <h2 class="h4 mb-3">Productos destacados</h2>
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/prod1/600/400" class="card-img-top" alt="Producto 1">
                        <div class="card-body">
                            <h6 class="card-title">Taladro percutor</h6>
                            <p class="text-success fw-bold mb-2">$1,299.00</p>
                            <a class="btn btn-outline-primary btn-sm" href="#">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/prod2/600/400" class="card-img-top" alt="Producto 2">
                        <div class="card-body">
                            <h6 class="card-title">Juego de llaves</h6>
                            <p class="text-success fw-bold mb-2">$399.00</p>
                            <a class="btn btn-outline-primary btn-sm" href="#">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/prod3/600/400" class="card-img-top" alt="Producto 3">
                        <div class="card-body">
                            <h6 class="card-title">Pintura vinílica</h6>
                            <p class="text-success fw-bold mb-2">$289.00</p>
                            <a class="btn btn-outline-primary btn-sm" href="#">Ver más</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <img src="https://picsum.photos/seed/prod4/600/400" class="card-img-top" alt="Producto 4">
                        <div class="card-body">
                            <h6 class="card-title">Cinta aislante</h6>
                            <p class="text-success fw-bold mb-2">$29.00</p>
                            <a class="btn btn-outline-primary btn-sm" href="#">Ver más</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- COMPONENTES CON JS (para verificar que el JS de Bootstrap está cargado) -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <h3 class="h6 mb-3">Collapse</h3>
                <button class="btn btn-secondary mb-2" data-bs-toggle="collapse" data-bs-target="#demoCollapse">Ver detalles</button>
                <div class="collapse" id="demoCollapse">
                    <div class="card card-body">
                        Si ves este bloque al hacer clic, el JS de Bootstrap está funcionando.
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="h6 mb-3">Modal</h3>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#demoModal">Abrir modal</button>
                <div class="modal fade" id="demoModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Prueba de Modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                Si ves este modal, Bootstrap JS está activo.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CAROUSEL (auto) -->
        <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner rounded-3 overflow-hidden">
                <div class="carousel-item active">
                    <img src="https://picsum.photos/seed/slide1/1200/400" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Promociones</h5><p>Ofertas por temporada</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://picsum.photos/seed/slide2/1200/400" class="d-block w-100" alt="Slide 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Nuevos productos</h5><p>Llega la última línea</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://picsum.photos/seed/slide3/1200/400" class="d-block w-100" alt="Slide 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Envíos</h5><p>A todo México</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <!-- FOOTER SIMPLE -->
        <footer class="border-top pt-3 mt-4 text-center text-muted small">
            © {{ date('Y') }} Ferretería Hernández
        </footer>

    </div>
@endsection
