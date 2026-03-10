<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

// Estadísticas simples de ventas por día y por categoría
$ventasPorDia = $pdo->query("
    SELECT DATE(created_at) AS fecha,
           COUNT(*) AS pedidos,
           COALESCE(SUM(total_amount),0) AS total
    FROM orders
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) DESC
    LIMIT 14
")->fetchAll();

$ventasPorCategoria = $pdo->query("
    SELECT c.name AS categoria,
           COALESCE(SUM(oi.quantity * oi.unit_price),0) AS total
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    LEFT JOIN categories c ON c.id = p.category_id
    GROUP BY c.name
    ORDER BY total DESC
")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Estadísticas</h1>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Ventas por día (últimos 14 días)</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            <th class="text-end">Pedidos</th>
                            <th class="text-end">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!$ventasPorDia): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Sin datos de ventas aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventasPorDia as $row): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                    <td class="text-end"><?= (int)$row['pedidos'] ?></td>
                                    <td class="text-end">$<?= number_format($row['total'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Ventas por categoría</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                        <tr>
                            <th>Categoría</th>
                            <th class="text-end">Total vendido</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!$ventasPorCategoria): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted">Sin datos de ventas aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventasPorCategoria as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['categoria'] ?? 'Sin categoría') ?></td>
                                    <td class="text-end">$<?= number_format($row['total'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials_footer.php'; ?>

