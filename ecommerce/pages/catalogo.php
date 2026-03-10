<?php
$pdo = getPDO();

$categoriaId = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$tipoCliente = $_GET['tipo'] ?? 'minorista'; // minorista | mayorista

$categorias = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$sql = "SELECT p.*,
               c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1";
$params = [];

if ($categoriaId) {
    $sql .= " AND p.category_id = :cat";
    $params[':cat'] = $categoriaId;
}

$sql .= " ORDER BY p.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();

function calcularPrecioVisible(array $producto, string $tipoCliente): float {
    $precioBase = $tipoCliente === 'mayorista' && !empty($producto['wholesale_price'])
        ? (float)$producto['wholesale_price']
        : (float)$producto['price'];

    if (!empty($producto['discount_percent'])) {
        $precioBase = $precioBase * (1 - (float)$producto['discount_percent'] / 100);
    }

    return $precioBase;
}
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3 mb-3">
    <h1 class="h3 mb-2 mb-md-0">Catálogo de productos</h1>
    <form class="d-flex gap-2" method="get" action="<?= $baseUrl ?>/index.php">
        <input type="hidden" name="page" value="catalogo">
        <select name="cat" class="form-select form-select-sm">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= $categoriaId === (int)$cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="tipo" class="form-select form-select-sm">
            <option value="minorista" <?= $tipoCliente === 'minorista' ? 'selected' : '' ?>>Precio minorista</option>
            <option value="mayorista" <?= $tipoCliente === 'mayorista' ? 'selected' : '' ?>>Precio mayorista</option>
        </select>
        <button class="btn btn-sm btn-primary">Filtrar</button>
    </form>
</div>

<div class="row g-4">
    <?php if (!$productos): ?>
        <p class="text-muted">No se encontraron productos con los filtros seleccionados.</p>
    <?php else: ?>
        <?php foreach ($productos as $product): ?>
            <?php
            $precioVisible = calcularPrecioVisible($product, $tipoCliente);
            $precioBaseSinDescuento = $tipoCliente === 'mayorista' && !empty($product['wholesale_price'])
                ? (float)$product['wholesale_price']
                : (float)$product['price'];
            ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100 position-relative">
                    <?php if (!empty($product['discount_percent'])): ?>
                        <span class="badge bg-danger badge-oferta">-<?= (int)$product['discount_percent'] ?>%</span>
                    <?php endif; ?>
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?= $baseUrl . '/uploads/products/' . htmlspecialchars($product['image']) ?>"
                             class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text small text-muted mb-2">
                            <?= nl2br(htmlspecialchars(substr($product['description'], 0, 80))) ?>...
                        </p>
                        <div class="mt-auto">
                            <?php if ($precioVisible < $precioBaseSinDescuento): ?>
                                <div>
                                    <span class="text-muted text-decoration-line-through">
                                        $<?= number_format($precioBaseSinDescuento, 2, ',', '.') ?>
                                    </span>
                                    <span class="fw-bold text-danger ms-1">
                                        $<?= number_format($precioVisible, 2, ',', '.') ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="fw-bold">
                                    $<?= number_format($precioVisible, 2, ',', '.') ?>
                                </div>
                            <?php endif; ?>
                            <a href="<?= $baseUrl ?>/index.php?page=carrito&add=<?= (int)$product['id'] ?>"
                               class="btn btn-sm btn-primary w-100 mt-2">
                                Agregar al carrito
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

