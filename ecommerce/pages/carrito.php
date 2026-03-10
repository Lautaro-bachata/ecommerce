<?php
$pdo = getPDO();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // [product_id => qty]
}

// Añadir producto
if (isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    if ($productId > 0) {
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
        header('Location: ' . $baseUrl . '/index.php?page=carrito');
        exit;
    }
}

// Actualizar cantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $productId => $qty) {
        $productId = (int)$productId;
        $qty = max(0, (int)$qty);
        if ($qty === 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $qty;
        }
    }
}

// Vaciar carrito
if (isset($_GET['empty']) && $_GET['empty'] === '1') {
    $_SESSION['cart'] = [];
    header('Location: ' . $baseUrl . '/index.php?page=carrito');
    exit;
}

$cartItems = [];
$total = 0;

if ($_SESSION['cart']) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll();

    foreach ($productos as $p) {
        $qty = $_SESSION['cart'][$p['id']] ?? 0;
        if ($qty <= 0) {
            continue;
        }
        $precio = (float)$p['price'];
        if (!empty($p['discount_percent'])) {
            $precio = $precio * (1 - (float)$p['discount_percent'] / 100);
        }
        $subtotal = $precio * $qty;
        $total += $subtotal;
        $cartItems[] = [
            'product' => $p,
            'qty' => $qty,
            'price' => $precio,
            'subtotal' => $subtotal,
        ];
    }
}
?>

<div class="mt-3 mb-3 d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0">Carrito de compras</h1>
    <?php if ($cartItems): ?>
        <a href="<?= $baseUrl ?>/index.php?page=carrito&empty=1" class="btn btn-sm btn-outline-danger">
            Vaciar carrito
        </a>
    <?php endif; ?>
</div>

<?php if (!$cartItems): ?>
    <p class="text-muted">Tu carrito está vacío. Agrega productos desde el catálogo u ofertas.</p>
<?php else: ?>
    <form method="post">
        <input type="hidden" name="update_cart" value="1">
        <div class="table-responsive mb-3">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <?php $p = $item['product']; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if (!empty($p['image'])): ?>
                                    <img src="<?= $baseUrl . '/uploads/products/' . htmlspecialchars($p['image']) ?>"
                                         alt="<?= htmlspecialchars($p['name']) ?>" width="48" class="me-2 rounded">
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                                    <?php if (!empty($p['sku'])): ?>
                                        <div class="small text-muted">SKU: <?= htmlspecialchars($p['sku']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-center" style="max-width: 90px;">
                            <input type="number" min="0" name="qty[<?= (int)$p['id'] ?>]"
                                   class="form-control form-control-sm text-center"
                                   value="<?= (int)$item['qty'] ?>">
                        </td>
                        <td class="text-end">
                            $<?= number_format($item['price'], 2, ',', '.') ?>
                        </td>
                        <td class="text-end">
                            $<?= number_format($item['subtotal'], 2, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="mb-2 mb-md-0">
                <button class="btn btn-outline-secondary btn-sm" type="submit">
                    Actualizar cantidades
                </button>
            </div>
            <div class="text-end">
                <div class="h5 mb-2">Total: $<?= number_format($total, 2, ',', '.') ?></div>
                <a href="<?= $baseUrl ?>/checkout_whatsapp.php" class="btn btn-success">
                    Finalizar pedido por WhatsApp
                </a>
                <p class="small text-muted mt-1 mb-0">
                    Al finalizar el pedido se abrirá WhatsApp con el resumen del pedido listo para enviar al comercio.
                </p>
            </div>
        </div>
    </form>
<?php endif; ?>

