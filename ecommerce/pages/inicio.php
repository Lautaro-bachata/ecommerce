<?php
$pdo = getPDO();

// Tomar textos de portada desde settings (si está disponible)
if (!isset($settings)) {
    try {
        $stmtInicio = $pdo->query("SELECT * FROM settings LIMIT 1");
        $settings = $stmtInicio->fetch();
    } catch (Throwable $e) {
        $settings = null;
    }
}

$heroTitle = $settings['home_hero_title'] ?? 'Ecommerce profesional para tu comercio';
$heroSubtitle = $settings['home_hero_subtitle'] ?? 'Vende al por menor y por mayor con un catálogo atractivo, carrito de compras y administración centralizada. Simple de usar, potente para crecer.';
$heroImage = $settings['home_hero_image'] ?? null;

$destacados = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 8")->fetchAll();
$ofertas = $pdo->query("SELECT * FROM products WHERE is_active = 1 AND discount_percent > 0 ORDER BY discount_percent DESC LIMIT 4")->fetchAll();
?>
<section class="hero-section">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="mb-3"><?= htmlspecialchars($heroTitle) ?></h1>
            <p class="lead mb-4">
                <?= nl2br(htmlspecialchars($heroSubtitle)) ?>
            </p>
            <a href="<?= $baseUrl ?>/index.php?page=catalogo" class="btn btn-warning btn-lg me-2 mb-2">
                Ver catálogo
            </a>
            <a href="<?= $baseUrl ?>/index.php?page=ofertas" class="btn btn-outline-light btn-lg mb-2">
                Ver ofertas
            </a>
        </div>
        <div class="col-md-5 text-center mt-4 mt-md-0">
            <div class="bg-light text-dark rounded-3 p-4 shadow-sm">
                <?php if ($heroImage): ?>
                    <img src="<?= $baseUrl . '/uploads/' . htmlspecialchars($heroImage) ?>" alt="" class="img-fluid rounded mb-3">
                <?php endif; ?>
                <h5 class="fw-bold mb-3">Ideal para:</h5>
                <ul class="list-unstyled mb-0 text-start">
                    <li>• Comercios minoristas</li>
                    <li>• Distribuidores y mayoristas</li>
                    <li>• Tiendas físicas que quieren vender online</li>
                    <li>• Negocios que necesitan control de stock y caja</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Productos destacados</h2>
        <a href="<?= $baseUrl ?>/index.php?page=catalogo" class="small">Ver todo el catálogo</a>
    </div>
    <div class="row g-4">
        <?php if (!$destacados): ?>
            <p class="text-muted">Aún no hay productos cargados. Podrás gestionarlos desde la sección de Administración.</p>
        <?php else: ?>
            <?php foreach ($destacados as $product): ?>
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
                                <?= nl2br(htmlspecialchars(substr($product['description'], 0, 60))) ?>...
                            </p>
                            <?php
                            $precio = (float)$product['price'];
                            $precioOferta = $precio;
                            if (!empty($product['discount_percent'])) {
                                $precioOferta = $precio * (1 - (float)$product['discount_percent'] / 100);
                            }
                            ?>
                            <div class="mt-auto">
                                <?php if ($precioOferta < $precio): ?>
                                    <div>
                                        <span class="text-muted text-decoration-line-through">$<?= number_format($precio, 2, ',', '.') ?></span>
                                        <span class="fw-bold text-danger ms-1">$<?= number_format($precioOferta, 2, ',', '.') ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="fw-bold">$<?= number_format($precio, 2, ',', '.') ?></div>
                                <?php endif; ?>
                                <a href="<?= $baseUrl ?>/index.php?page=carrito&add=<?= (int)$product['id'] ?>" class="btn btn-sm btn-primary w-100 mt-2">
                                    Agregar al carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Ofertas especiales</h2>
        <a href="<?= $baseUrl ?>/index.php?page=ofertas" class="small">Ver todas las ofertas</a>
    </div>
    <div class="row g-4">
        <?php if (!$ofertas): ?>
            <p class="text-muted">Aún no hay productos en oferta.</p>
        <?php else: ?>
            <?php foreach ($ofertas as $product): ?>
                <div class="col-6 col-md-3">
                    <div class="card product-card h-100 position-relative border-danger">
                        <span class="badge bg-danger badge-oferta">-<?= (int)$product['discount_percent'] ?>%</span>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= $baseUrl . '/uploads/products/' . htmlspecialchars($product['image']) ?>"
                                 class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <?php
                            $precio = (float)$product['price'];
                            $precioOferta = $precio * (1 - (float)$product['discount_percent'] / 100);
                            ?>
                            <div class="mt-auto">
                                <div>
                                    <span class="text-muted text-decoration-line-through">$<?= number_format($precio, 2, ',', '.') ?></span>
                                    <span class="fw-bold text-danger ms-1">$<?= number_format($precioOferta, 2, ',', '.') ?></span>
                                </div>
                                <a href="<?= $baseUrl ?>/index.php?page=carrito&add=<?= (int)$product['id'] ?>" class="btn btn-sm btn-outline-danger w-100 mt-2">
                                    Agregar al carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

