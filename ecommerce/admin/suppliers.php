<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

// Listado de proveedores
$stmt = $pdo->query("SELECT * FROM suppliers ORDER BY name");
$suppliers = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Proveedores</h1>
    <a href="<?= $baseUrl ?>/admin/supplier_form.php" class="btn btn-sm btn-primary">
        Nuevo proveedor
    </a>
</div>

<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead>
        <tr>
            <th>Nombre / Razón social</th>
            <th>Contacto</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Condiciones</th>
            <th class="text-end">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$suppliers): ?>
            <tr>
                <td colspan="6" class="text-center text-muted">Aún no hay proveedores cargados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($suppliers as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td><?= htmlspecialchars($s['contact_person'] ?? '') ?></td>
                    <td><?= htmlspecialchars($s['phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($s['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($s['conditions'] ?? '') ?></td>
                    <td class="text-end">
                        <a href="<?= $baseUrl ?>/admin/supplier_form.php?id=<?= (int)$s['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/partials_footer.php'; ?>

