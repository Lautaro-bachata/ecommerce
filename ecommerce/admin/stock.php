<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

// Productos con filtros de stock bajo / todos
$filtro = $_GET['f'] ?? 'bajo'; // bajo | todos

$sql = "SELECT p.*
        FROM products p
        WHERE p.is_active = 1";

if ($filtro === 'bajo') {
    $sql .= " AND p.stock_quantity <= p.stock_min_alert";
}

$sql .= " ORDER BY p.stock_quantity ASC, p.name";

$productos = $pdo->query($sql)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Stock y alertas</h1>
</div>

<div class="mb-3">
    <a href="<?= $baseUrl ?>/admin/stock.php?f=bajo"
       class="btn btn-sm <?= $filtro === 'bajo' ? 'btn-primary' : 'btn-outline-primary' ?>">
        Solo productos con stock bajo
    </a>
    <a href="<?= $baseUrl ?>/admin/stock.php?f=todos"
       class="btn btn-sm <?= $filtro === 'todos' ? 'btn-primary' : 'btn-outline-primary' ?>">
        Ver todos
    </a>
    <a href="<?= $baseUrl ?>/admin/stock_movement.php" class="btn btn-sm btn-success">
        Registrar movimiento de stock
    </a>
</div>

<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead>
        <tr>
            <th>Producto</th>
            <th>SKU</th>
            <th class="text-end">Stock actual</th>
            <th class="text-end">Mínimo</th>
            <th class="text-center">Alerta</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$productos): ?>
            <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron productos.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($productos as $p): ?>
                <?php
                $bajo = $p['stock_quantity'] <= $p['stock_min_alert'];
                ?>
                <tr class="<?= $bajo ? 'table-warning' : '' ?>">
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['sku']) ?></td>
                    <td class="text-end"><?= (int)$p['stock_quantity'] ?></td>
                    <td class="text-end"><?= (int)$p['stock_min_alert'] ?></td>
                    <td class="text-center">
                        <?php if ($bajo): ?>
                            <span class="badge bg-warning text-dark">Stock bajo</span>
                        <?php else: ?>
                            <span class="badge bg-success">OK</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/partials_footer.php'; ?>

