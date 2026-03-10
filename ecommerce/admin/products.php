<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

// Listado simple de productos con filtros básicos
$search = trim($_GET['q'] ?? '');

$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (p.name LIKE :q OR p.sku LIKE :q)";
    $params[':q'] = '%' . $search . '%';
}

$sql .= " ORDER BY p.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Productos</h1>
    <a href="<?= $baseUrl ?>/admin/product_form.php" class="btn btn-primary btn-sm">
        Nuevo producto
    </a>
</div>

<form class="row g-2 mb-3" method="get">
    <div class="col-md-4">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control form-control-sm"
               placeholder="Buscar por nombre o SKU">
    </div>
    <div class="col-md-2">
        <button class="btn btn-sm btn-outline-secondary">Buscar</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>SKU</th>
            <th>Categoría</th>
            <th class="text-end">Precio min.</th>
            <th class="text-end">Precio may.</th>
            <th class="text-end">Stock</th>
            <th class="text-center">Activo</th>
            <th class="text-end">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$products): ?>
            <tr>
                <td colspan="8" class="text-center text-muted">Aún no hay productos cargados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['sku']) ?></td>
                    <td><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                    <td class="text-end">$<?= number_format((float)$p['price'], 2, ',', '.') ?></td>
                    <td class="text-end">
                        <?php if (!empty($p['wholesale_price'])): ?>
                            $<?= number_format((float)$p['wholesale_price'], 2, ',', '.') ?>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end"><?= (int)$p['stock_quantity'] ?></td>
                    <td class="text-center">
                        <?php if ($p['is_active']): ?>
                            <span class="badge bg-success">Sí</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">No</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= $baseUrl ?>/admin/product_form.php?id=<?= (int)$p['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Editar</a>
                        <a href="<?= $baseUrl ?>/admin/product_toggle.php?id=<?= (int)$p['id'] ?>"
                           class="btn btn-sm btn-outline-secondary">Activar/Desactivar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/partials_footer.php'; ?>

