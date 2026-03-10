<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

$productos = $pdo->query("SELECT id, name, sku FROM products WHERE is_active = 1 ORDER BY name")->fetchAll();

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Registrar movimiento de stock</h1>
    <a href="<?= $baseUrl ?>/admin/stock.php" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>

<form method="post" action="<?= $baseUrl ?>/admin/stock_movement_save.php">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Producto</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Seleccionar producto</option>
                            <?php foreach ($productos as $p): ?>
                                <option value="<?= (int)$p['id'] ?>">
                                    <?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['sku']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de movimiento</label>
                        <select name="type" class="form-select" required>
                            <option value="ingreso">Ingreso (compra / ajuste positivo)</option>
                            <option value="egreso">Egreso (ajuste negativo / roturas)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="movement_date" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo / Detalle</label>
                        <textarea name="note" rows="4" class="form-control"
                                  placeholder="Ej: Compra a proveedor X, ajuste de inventario, etc."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        Guardar movimiento
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require __DIR__ . '/partials_footer.php'; ?>

