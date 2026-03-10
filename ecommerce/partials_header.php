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

// Provide default value for storeName if not set
$storeName = $storeName ?? 'Mi Tienda';

$primaryColor = $settings['primary_color'] ?? '#1d3557';
$secondaryColor = $settings['secondary_color'] ?? '#457b9d';
$currentUser = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($storeName) ?></title>
    <meta name="description" content="Tienda online profesional para comercios minoristas y mayoristas. Productos, ofertas y compras fáciles.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/styles.css">
    <style>
        :root {
            --primary-color: <?= htmlspecialchars($primaryColor) ?>;
            --secondary-color: <?= htmlspecialchars($secondaryColor) ?>;
        }
        
        /* Navbar mejorado */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            margin: 0 8px;
            transition: all 0.3s ease;
            border-radius: 25px;
            padding: 8px 16px !important;
        }
        
        .navbar-nav .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        .btn-admin {
            background: rgba(255,255,255,0.2);
            border-radius: 25px;
            padding: 8px 16px !important;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        #cart-count {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Botones principales */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 25px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        /* Enlaces generales */
        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        a:hover {
            color: var(--secondary-color);
            transform: translateY(-1px);
        }
        
        /* Footer mejorado */
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            margin-top: auto;
        }
        
        /* Cards y productos */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        /* Badges y precios */
        .badge {
            border-radius: 20px;
            padding: 5px 12px;
            font-weight: 600;
        }
        
        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .old-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        /* Alertas */
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        /* Formularios */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        /* Dropdowns */
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-top: 10px;
        }
        
        .dropdown-item {
            border-radius: 8px;
            margin: 2px 5px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <?= htmlspecialchars($storeName) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=nosotros">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=catalogo">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=ofertas">Ofertas</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=carrito">
                        <i class="fas fa-shopping-cart"></i> Carrito
                        <span class="badge bg-danger ms-1" id="cart-count">0</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <?= $currentUser ? htmlspecialchars($currentUser['name']) : 'Cuenta' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (!$currentUser): ?>
                            <li><a class="dropdown-item" href="index.php?page=login"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="index.php?page=registro"><i class="fas fa-user-plus"></i> Registrarse</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="index.php?page=panel-usuario"><i class="fas fa-user"></i> Panel de usuario</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="user_logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-admin" href="admin/index.php">
                        <i class="fas fa-cog"></i> Administración
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="mt-5 pt-4">
    <div class="container mb-5">

