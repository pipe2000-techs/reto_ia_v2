<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mini Gestor de Proyectos')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.12); }
        .progress { height: 8px; }
        .badge-prioridad-alta { background-color: #dc3545; }
        .badge-prioridad-media { background-color: #fd7e14; }
        .badge-prioridad-baja { background-color: #198754; }
        .badge-estado-backlog { background-color: #6c757d; }
        .badge-estado-en_progreso { background-color: #0d6efd; }
        .badge-estado-testing { background-color: #ffc107; color: #000; }
        .badge-estado-terminada { background-color: #198754; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="bi bi-kanban-fill me-2"></i>Gestor de Proyectos
        </a>
        <div class="ms-auto">
            <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-house me-1"></i>Inicio
            </a>
        </div>
    </div>
</nav>

<main class="container pb-5">
    @yield('content')
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // CSRF para todas las peticiones AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>

@yield('scripts')
</body>
</html>
