<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administración - <?= htmlspecialchars($storeName) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/styles.css">
    <style>
        body {
            background-color: #f1f3f5;
        }
        .admin-sidebar {
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-light admin-sidebar p-0">
            <div class="p-3 border-bottom border-secondary">
                <h2 class="h5 mb-0"><?= htmlspecialchars($storeName) ?></h2>
                <small class="text-muted">Panel de administración</small>
            </div>
            <ul class="nav flex-column p-2">
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/index.php">Dashboard</a>
                </li>
                <li class="nav-item mt-2">
                    <span class="text-uppercase text-muted small ms-2">Catálogo</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/products.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/stock.php">Stock y alertas</a>
                </li>
                <li class="nav-item mt-2">
                    <span class="text-uppercase text-muted small ms-2">Caja y gastos</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/cash.php">Caja y movimientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/stats.php">Estadísticas</a>
                </li>
                <li class="nav-item mt-2">
                    <span class="text-uppercase text-muted small ms-2">Relaciones</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/suppliers.php">Proveedores</a>
                </li>
                <li class="nav-item mt-2">
                    <span class="text-uppercase text-muted small ms-2">Personalización</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $baseUrl ?>/admin/settings.php">Diseño y ajustes</a>
                </li>
            </ul>
            <div class="p-3 border-top border-secondary mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Conectado como:</div>
                        <div class="small fw-semibold">
                            <?= htmlspecialchars($_SESSION['admin']['name'] ?? 'Administrador') ?>
                        </div>
                    </div>
                    <a href="<?= $baseUrl ?>/admin/logout.php" class="btn btn-sm btn-outline-light">
                        Salir
                    </a>
                </div>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

