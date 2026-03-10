<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$product = null;

if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch();
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><?= $editing ? 'Editar producto' : 'Nuevo producto' ?></h1>
    <a href="<?= $baseUrl ?>/admin/products.php" class="btn btn-sm btn-outline-secondary">Volver al listado</a>
</div>

<form method="post" action="<?= $baseUrl ?>/admin/product_save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $editing ? (int)$product['id'] : 0 ?>">
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del producto</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= $editing ? htmlspecialchars($product['name']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="4" class="form-control"><?= $editing ? htmlspecialchars($product['description']) : '' ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">SKU / Código interno</label>
                            <input type="text" name="sku" class="form-control"
                                   value="<?= $editing ? htmlspecialchars($product['sku']) : '' ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="category_id" class="form-select">
                                <option value="">Sin categoría</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= (int)$cat['id'] ?>"
                                        <?= $editing && $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="is_active" class="form-select">
                                <option value="1" <?= !$editing || $product['is_active'] ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= $editing && !$product['is_active'] ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Precios</h5>
                    <div class="mb-3">
                        <label class="form-label">Precio minorista</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control" required
                               value="<?= $editing ? htmlspecialchars($product['price']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio mayorista (opcional)</label>
                        <input type="number" step="0.01" min="0" name="wholesale_price" class="form-control"
                               value="<?= $editing && $product['wholesale_price'] !== null ? htmlspecialchars($product['wholesale_price']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" step="1" min="0" max="100" name="discount_percent" class="form-control"
                               value="<?= $editing ? htmlspecialchars($product['discount_percent']) : '0' ?>">
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Stock</h5>
                    <div class="mb-3">
                        <label class="form-label">Stock actual</label>
                        <input type="number" step="1" min="0" name="stock_quantity" class="form-control"
                               value="<?= $editing ? (int)$product['stock_quantity'] : '0' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock mínimo (alerta)</label>
                        <input type="number" step="1" min="0" name="stock_min_alert" class="form-control"
                               value="<?= $editing ? (int)$product['stock_min_alert'] : '0' ?>">
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Imagen principal</h5>
                    <?php if ($editing && !empty($product['image'])): ?>
                        <div class="mb-2">
                            <img src="<?= $baseUrl . '/uploads/products/' . htmlspecialchars($product['image']) ?>"
                                 alt="" class="img-fluid rounded">
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Formatos recomendados: JPG, PNG. Peso máx. 1-2MB.</small>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">
                Guardar producto
            </button>
        </div>
    </div>
</form>

<?php require __DIR__ . '/partials_footer.php'; ?>

