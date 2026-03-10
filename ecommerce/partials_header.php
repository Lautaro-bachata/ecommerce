<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$pdoHeader = getPDO();
$settings = null;
try {
    $stmtHeader = $pdoHeader->query("SELECT * FROM settings LIMIT 1");
    $settings = $stmtHeader->fetch();
} catch (Throwable $e) {
    $settings = null;
}

if ($settings) {
    if (!empty($settings['store_name'])) {
        $storeName = $settings['store_name'];
    }
    if (!empty($settings['whatsapp'])) {
        $ownerWhatsapp = $settings['whatsapp'];
    }
}

$storeName = $storeName ?? 'Mi Tienda';
$primaryColor = $settings['primary_color'] ?? '#1d3557';
$secondaryColor = $settings['secondary_color'] ?? '#457b9d';
$logo = $settings['logo'] ?? null;
$currentUser = $_SESSION['user'] ?? null;
$currentAdmin = $_SESSION['admin'] ?? null;
$currentPage = $_GET['page'] ?? 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($storeName) ?> - Tienda Online Profesional</title>
    <meta name="description" content="<?= htmlspecialchars($storeName) ?> - Tienda online profesional para comercios minoristas y mayoristas. Productos de calidad, ofertas exclusivas y compras fáciles.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/styles.css">
    <style>
        :root {
            --primary-color: <?= htmlspecialchars($primaryColor) ?>;
            --secondary-color: <?= htmlspecialchars($secondaryColor) ?>;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= $baseUrl ?>/index.php">
            <?php if ($logo): ?>
                <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($storeName) ?>" height="40" class="me-2">
            <?php else: ?>
                <i class="fas fa-store me-2"></i>
            <?php endif; ?>
            <span><?= htmlspecialchars($storeName) ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'inicio' ? 'active' : '' ?>" href="<?= $baseUrl ?>/index.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'catalogo' ? 'active' : '' ?>" href="<?= $baseUrl ?>/index.php?page=catalogo">
                        <i class="fas fa-th"></i> Catálogo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'ofertas' ? 'active' : '' ?>" href="<?= $baseUrl ?>/index.php?page=ofertas">
                        <i class="fas fa-tags"></i> Ofertas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'nosotros' ? 'active' : '' ?>" href="<?= $baseUrl ?>/index.php?page=nosotros">
                        <i class="fas fa-info-circle"></i> Nosotros
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?= $baseUrl ?>/index.php?page=carrito">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count" style="display: none;">0</span>
                    </a>
                </li>
                <?php if ($currentUser || $currentAdmin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                            <span><?= htmlspecialchars($currentUser['name'] ?? $currentAdmin['name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($currentAdmin): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= $baseUrl ?>/admin/index.php">
                                        <i class="fas fa-tachometer-alt me-2"></i> Panel de Admin
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php elseif ($currentUser): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= $baseUrl ?>/index.php?page=panel-usuario">
                                        <i class="fas fa-user me-2"></i> Mi Cuenta
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-box me-2"></i> Mis Pedidos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= $baseUrl ?>/user_logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm ms-2" href="<?= $baseUrl ?>/index.php?page=login">
                            <i class="fas fa-sign-in-alt"></i> Ingresar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main style="padding-top: 76px;">
    <div class="container-fluid px-0">

