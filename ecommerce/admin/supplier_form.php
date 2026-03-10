<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$supplier = null;

if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $supplier = $stmt->fetch();
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><?= $editing ? 'Editar proveedor' : 'Nuevo proveedor' ?></h1>
    <a href="<?= $baseUrl ?>/admin/suppliers.php" class="btn btn-sm btn-outline-secondary">Volver al listado</a>
</div>

<form method="post" action="<?= $baseUrl ?>/admin/supplier_save.php">
    <input type="hidden" name="id" value="<?= $editing ? (int)$supplier['id'] : 0 ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre / Razón social</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= $editing ? htmlspecialchars($supplier['name']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persona de contacto</label>
                        <input type="text" name="contact_person" class="form-control"
                               value="<?= $editing ? htmlspecialchars($supplier['contact_person']) : '' ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= $editing ? htmlspecialchars($supplier['phone']) : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= $editing ? htmlspecialchars($supplier['email']) : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Condiciones comerciales</label>
                        <textarea name="conditions" rows="4" class="form-control"
                                  placeholder="Ej: Plazo de pago, descuentos por volumen, forma de entrega, etc."><?= $editing ? htmlspecialchars($supplier['conditions']) : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas internas</label>
                        <textarea name="notes" rows="3" class="form-control"
                                  placeholder="Información útil solo para uso interno."><?= $editing ? htmlspecialchars($supplier['notes']) : '' ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        Guardar proveedor
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require __DIR__ . '/partials_footer.php'; ?>

