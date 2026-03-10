<?php
$pdo = getPDO();

if (!isset($settings)) {
    try {
        $stmtInicio = $pdo->query("SELECT * FROM settings LIMIT 1");
        $settings = $stmtInicio->fetch();
    } catch (Throwable $e) {
        $settings = null;
    }
}

$heroTitle = $settings['home_hero_title'] ?? 'Tu Tienda Online Profesional';
$heroSubtitle = $settings['home_hero_subtitle'] ?? 'Descubrí nuestra amplia selección de productos con las mejores ofertas. Compra fácil, rápido y seguro.';
$heroImage = $settings['home_hero_image'] ?? null;

$destacados = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 8")->fetchAll();
$ofertas = $pdo->query("SELECT * FROM products WHERE is_active = 1 AND discount_percent > 0 ORDER BY discount_percent DESC LIMIT 4")->fetchAll();
$categorias = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name LIMIT 6")->fetchAll();
?>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="animate-on-scroll">
                    <h1 class="display-3 fw-bold mb-4"><?= htmlspecialchars($heroTitle) ?></h1>
                    <p class="lead mb-4 opacity-90">
                        <?= nl2br(htmlspecialchars($heroSubtitle)) ?>
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= $baseUrl ?>/index.php?page=catalogo" class="btn btn-warning btn-lg px-5">
                            <i class="fas fa-th me-2"></i> Ver Catálogo
                        </a>
                        <a href="<?= $baseUrl ?>/index.php?page=ofertas" class="btn btn-outline-light btn-lg px-5">
                            <i class="fas fa-tags me-2"></i> Ver Ofertas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image animate-on-scroll">
                    <?php if ($heroImage): ?>
                        <img src="<?= $baseUrl . '/uploads/' . htmlspecialchars($heroImage) ?>"
                             alt="<?= htmlspecialchars($heroTitle) ?>"
                             class="img-fluid">
                    <?php else: ?>
                        <div class="bg-white bg-opacity-10 rounded p-5 text-center">
                            <i class="fas fa-shopping-bag fa-10x opacity-50"></i>
                            <h3 class="mt-4">Configura tu imagen de portada</h3>
                            <p class="opacity-75">Desde el panel de administración</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <?php if ($categorias): ?>
    <section class="py-5">
        <div class="text-center mb-5 animate-on-scroll">
            <h2 class="section-title d-inline-block">Categorías Populares</h2>
            <p class="text-muted mt-3">Explorá nuestras categorías más destacadas</p>
        </div>
        <div class="row g-4">
            <?php foreach ($categorias as $cat): ?>
                <div class="col-6 col-md-4 col-lg-2 animate-on-scroll">
                    <a href="<?= $baseUrl ?>/index.php?page=catalogo&cat=<?= (int)$cat['id'] ?>"
                       class="text-decoration-none">
                        <div class="feature-box">
                            <i class="fas fa-cube"></i>
                            <h6 class="mt-2 mb-0"><?= htmlspecialchars($cat['name']) ?></h6>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($ofertas): ?>
    <section class="py-5 animate-on-scroll">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title d-inline-block mb-0">Ofertas Especiales</h2>
                <p class="text-muted mb-0">No te pierdas estas increíbles ofertas</p>
            </div>
            <a href="<?= $baseUrl ?>/index.php?page=ofertas" class="btn btn-outline-primary">
                Ver Todas <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="row g-4">
            <?php foreach ($ofertas as $product): ?>
                <div class="col-6 col-md-4 col-lg-3 animate-on-scroll">
                    <div class="card product-card h-100 position-relative">
                        <span class="badge-oferta">-<?= (int)$product['discount_percent'] ?>%</span>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= $baseUrl . '/uploads/products/' . htmlspecialchars($product['image']) ?>"
                                 class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <?php
                            $precio = (float)$product['price'];
                            $precioOferta = $precio * (1 - (float)$product['discount_percent'] / 100);
                            ?>
                            <div class="mt-3">
                                <div class="mb-3">
                                    <span class="old-price">$<?= number_format($precio, 2, ',', '.') ?></span>
                                    <div class="price">$<?= number_format($precioOferta, 2, ',', '.') ?></div>
                                </div>
                                <a href="<?= $baseUrl ?>/index.php?page=carrito&add=<?= (int)$product['id'] ?>"
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i> Agregar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($destacados): ?>
    <section class="py-5 animate-on-scroll">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title d-inline-block mb-0">Productos Destacados</h2>
                <p class="text-muted mb-0">Los mejores productos seleccionados para vos</p>
            </div>
            <a href="<?= $baseUrl ?>/index.php?page=catalogo" class="btn btn-outline-primary">
                Ver Catálogo <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="row g-4">
            <?php foreach ($destacados as $product): ?>
                <div class="col-6 col-md-4 col-lg-3 animate-on-scroll">
                    <div class="card product-card h-100 position-relative">
                        <?php if (!empty($product['discount_percent'])): ?>
                            <span class="badge-oferta">-<?= (int)$product['discount_percent'] ?>%</span>
                        <?php endif; ?>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= $baseUrl . '/uploads/products/' . htmlspecialchars($product['image']) ?>"
                                 class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <?php if (!empty($product['description'])): ?>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($product['description'], 0, 60)) ?>...
                                </p>
                            <?php endif; ?>
                            <?php
                            $precio = (float)$product['price'];
                            $precioOferta = $precio;
                            if (!empty($product['discount_percent'])) {
                                $precioOferta = $precio * (1 - (float)$product['discount_percent'] / 100);
                            }
                            ?>
                            <div class="mt-auto">
                                <?php if ($precioOferta < $precio): ?>
                                    <div class="mb-3">
                                        <span class="old-price">$<?= number_format($precio, 2, ',', '.') ?></span>
                                        <div class="price text-danger">$<?= number_format($precioOferta, 2, ',', '.') ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="price mb-3">$<?= number_format($precio, 2, ',', '.') ?></div>
                                <?php endif; ?>
                                <a href="<?= $baseUrl ?>/index.php?page=carrito&add=<?= (int)$product['id'] ?>"
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i> Agregar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php else: ?>
    <section class="py-5 text-center">
        <div class="alert alert-info">
            <i class="fas fa-info-circle fa-3x mb-3"></i>
            <h4>Aún no hay productos cargados</h4>
            <p class="mb-0">Podrás gestionar los productos desde el panel de administración</p>
        </div>
    </section>
    <?php endif; ?>

    <section class="py-5 animate-on-scroll">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-shipping-fast"></i>
                    <h5 class="mt-3 mb-2">Envíos Rápidos</h5>
                    <p class="text-muted mb-0">Recibí tus productos en tiempo récord</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-shield-alt"></i>
                    <h5 class="mt-3 mb-2">Compra Segura</h5>
                    <p class="text-muted mb-0">Tus datos están protegidos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-headset"></i>
                    <h5 class="mt-3 mb-2">Atención al Cliente</h5>
                    <p class="text-muted mb-0">Estamos para ayudarte cuando lo necesites</p>
                </div>
            </div>
        </div>
    </section>
</div>
