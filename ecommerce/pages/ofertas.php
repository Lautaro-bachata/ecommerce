<?php
$pdo = getPDO();

$sql = "SELECT p.*,
               c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1
          AND p.discount_percent > 0
        ORDER BY p.discount_percent DESC, p.name";
$ofertas = $pdo->query($sql)->fetchAll();
?>

<div class="mt-3 mb-3">
    <h1 class="h3">Productos en oferta</h1>
    <p class="text-muted mb-0">
        Descubrí las mejores promociones para tus compras minoristas y mayoristas.
    </p>
</div>

<div class="row g-4">
    <?php if (!$ofertas): ?>
        <p class="text-muted">Por el momento no hay productos en oferta.</p>
    <?php else: ?>
        <?php foreach ($ofertas as $product): ?>
            <?php
            $precio = (float)$product['price'];
            $precioOferta = $precio * (1 - (float)$product['discount_percent'] / 100);
            ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100 position-relative border-danger">
                    <span class="badge bg-danger badge-oferta">
                        -<?= (int)$product['discount_percent'] ?>%
                    </span>
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
                            <div>
                                <span class="text-muted text-decoration-line-through">
                                    $<?= number_format($precio, 2, ',', '.') ?>
                                </span>
                                <span class="fw-bold text-danger ms-1">
                                    $<?= number_format($precioOferta, 2, ',', '.') ?>
                                </span>
                            </div>
                            <a href="<?= $baseUrl ?>/index.php?page=carrito&add=<?= (int)$product['id'] ?>"
                               class="btn btn-sm btn-outline-danger w-100 mt-2">
                                Agregar al carrito
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

