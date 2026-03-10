<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

// Resumen rápido: ventas del día, caja y stock bajo
$hoy = date('Y-m-d');

// Estos datos usarán tablas que definiremos al diseñar la base de datos
$ventasHoy = 0;
$totalIngresosHoy = 0.0;
$totalEgresosHoy = 0.0;
$stockBajo = 0;

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cantidad, COALESCE(SUM(total_amount),0) AS monto
                           FROM orders
                           WHERE DATE(created_at) = :hoy");
    $stmt->execute([':hoy' => $hoy]);
    $row = $stmt->fetch();
    if ($row) {
        $ventasHoy = (int)$row['cantidad'];
        $totalIngresosHoy = (float)$row['monto'];
    }

    $stmt = $pdo->prepare("SELECT
                                COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount ELSE 0 END),0) AS ingresos,
                                COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount ELSE 0 END),0) AS egresos
                           FROM cash_movements
                           WHERE DATE(movement_date) = :hoy");
    $stmt->execute([':hoy' => $hoy]);
    $row = $stmt->fetch();
    if ($row) {
        $totalIngresosHoy = (float)$row['ingresos'];
        $totalEgresosHoy = (float)$row['egresos'];
    }

    $stmt = $pdo->query("SELECT COUNT(*) AS cant
                         FROM products
                         WHERE is_active = 1
                           AND stock_quantity <= stock_min_alert");
    $row = $stmt->fetch();
    if ($row) {
        $stockBajo = (int)$row['cant'];
    }
} catch (Throwable $e) {
    // La base puede no estar creada aún; evitamos romper el dashboard.
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
    <div>
        <h1 class="h3">Dashboard</h1>
        <p class="text-muted mb-0">
            Resumen general del negocio. Controlá tus ventas, caja, stock y proveedores desde un solo lugar.
        </p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Ventas de hoy</h5>
                <div class="display-6 mb-1"><?= $ventasHoy ?></div>
                <p class="text-muted small mb-0">
                    Pedidos generados hoy en la tienda online.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Caja de hoy</h5>
                <div class="mb-1">
                    <span class="small text-muted">Ingresos:</span>
                    <span class="fw-semibold text-success">$<?= number_format($totalIngresosHoy, 2, ',', '.') ?></span>
                </div>
                <div class="mb-1">
                    <span class="small text-muted">Gastos:</span>
                    <span class="fw-semibold text-danger">$<?= number_format($totalEgresosHoy, 2, ',', '.') ?></span>
                </div>
                <p class="text-muted small mb-0">
                    Resumen rápido de movimientos de caja del día.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Alertas de stock</h5>
                <div class="display-6 mb-1"><?= $stockBajo ?></div>
                <p class="text-muted small mb-0">
                    Productos con stock por debajo del mínimo configurado.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Atajos rápidos</h5>
                <p class="small text-muted">
                    Operaciones frecuentes para gestionar tu negocio día a día.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= $baseUrl ?>/admin/products.php" class="btn btn-sm btn-outline-primary">Nuevo producto</a>
                    <a href="<?= $baseUrl ?>/admin/stock.php" class="btn btn-sm btn-outline-secondary">Ingresar mercadería</a>
                    <a href="<?= $baseUrl ?>/admin/cash.php" class="btn btn-sm btn-outline-success">Registrar ingreso</a>
                    <a href="<?= $baseUrl ?>/admin/cash.php?tab=gastos" class="btn btn-sm btn-outline-danger">Registrar gasto</a>
                    <a href="<?= $baseUrl ?>/admin/settings.php" class="btn btn-sm btn-outline-dark">Editar diseño del sitio</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Próximos pasos sugeridos</h5>
                <ul class="small mb-0">
                    <li>Cargar categorías y productos con sus fotos y precios.</li>
                    <li>Configurar el stock mínimo de cada producto para recibir alertas.</li>
                    <li>Registrar diariamente ingresos y gastos para ver reportes claros.</li>
                    <li>Cargar tus proveedores con datos de contacto y condiciones comerciales.</li>
                    <li>Personalizar logo, colores y textos de la página de inicio.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials_footer.php'; ?>

