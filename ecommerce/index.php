<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Router muy simple basado en parámetro "page"
$page = $_GET['page'] ?? 'inicio';

// Función para incluir vistas de forma segura
function render_view(string $view, array $data = []): void {
    extract($data);
    require __DIR__ . '/partials_header.php';
    $viewFile = __DIR__ . '/pages/' . $view . '.php';
    if (is_file($viewFile)) {
        require $viewFile;
    } else {
        echo '<div class="alert alert-danger mt-4">Página no encontrada.</div>';
    }
    require __DIR__ . '/partials_footer.php';
}

// Redirección rápida a secciones conocidas
switch ($page) {
    case 'inicio':
    case 'nosotros':
    case 'catalogo':
    case 'ofertas':
    case 'carrito':
    case 'login':
    case 'registro':
    case 'panel-usuario':
        render_view($page);
        break;
    default:
        render_view('inicio');
        break;
}

