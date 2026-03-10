<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

$type = $_GET['type'] ?? 'ingreso';
if (!in_array($type, ['ingreso', 'egreso'], true)) {
    $type = 'ingreso';
}

// Categorías de gastos/ingresos podrán manejarse desde configuración en el futuro
$categorias = [
    'Venta',
    'Pago proveedor',
    'Servicio público',
    'Alquiler',
    'Sueldos',
    'Impuestos',
    'Otro',
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">
        <?= $type === 'ingreso' ? 'Nuevo ingreso' : 'Nuevo gasto' ?>
    </h1>
    <a href="<?= $baseUrl ?>/admin/cash.php" class="btn btn-sm btn-outline-secondary">Volver a caja</a>
</div>

<form method="post" action="<?= $baseUrl ?>/admin/cash_movement_save.php">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="movement_date" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="category" class="form-select">
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Descripción / Detalle</label>
                        <textarea name="description" rows="5" class="form-control"
                                  placeholder="<?= $type === 'ingreso'
                                      ? 'Ej: Venta del día, ingreso por transferencia, etc.'
                                      : 'Ej: Pago de alquiler, servicio de luz, compra de mercadería, etc.' ?>"></textarea>
                    </div>
                    <button type="submit" class="btn <?= $type === 'ingreso' ? 'btn-success' : 'btn-danger' ?> w-100">
                        Guardar movimiento
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require __DIR__ . '/partials_footer.php'; ?>

