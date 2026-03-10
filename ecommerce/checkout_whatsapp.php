<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['cart']) || !$_SESSION['cart']) {
    header('Location: ' . $baseUrl . '/index.php?page=carrito');
    exit;
}

$pdo = getPDO();
$ids = array_map('intval', array_keys($_SESSION['cart']));
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$productos = $stmt->fetchAll();

if (!$productos) {
    header('Location: ' . $baseUrl . '/index.php?page=carrito');
    exit;
}

$total = 0;
$lineasProductos = [];

// Construimos detalle y, al mismo tiempo, armamos datos para guardar el pedido
$itemsParaOrden = [];

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

    $nombre = $p['name'];
    $sku = $p['sku'] ?? '';
    $skuTexto = $sku ? " (SKU: {$sku})" : '';

    $lineasProductos[] = "- {$nombre}{$skuTexto} x {$qty} = $" . number_format($subtotal, 2, ',', '.');

    $itemsParaOrden[] = [
        'product_id' => (int)$p['id'],
        'product_name' => $nombre,
        'sku' => $sku,
        'quantity' => $qty,
        'unit_price' => $precio,
        'discount_percent' => (int)($p['discount_percent'] ?? 0),
        'subtotal' => $subtotal,
    ];
}

if (!$lineasProductos) {
    header('Location: ' . $baseUrl . '/index.php?page=carrito');
    exit;
}

$usuario = $_SESSION['user'] ?? null;

// Guardar pedido en la base de datos (para estadísticas)
$orderId = null;

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders
        (user_id, customer_name, customer_email, customer_phone, customer_type, whatsapp, status, total_amount, created_at)
        VALUES (:user_id, :customer_name, :customer_email, :customer_phone, :customer_type, :whatsapp, :status, :total_amount, NOW())");

    $stmt->execute([
        ':user_id' => $usuario['id'] ?? null,
        ':customer_name' => $usuario['name'] ?? null,
        ':customer_email' => $usuario['email'] ?? null,
        ':customer_phone' => $usuario['phone'] ?? null,
        ':customer_type' => $usuario['customer_type'] ?? null,
        ':whatsapp' => $ownerWhatsapp,
        ':status' => 'pendiente',
        ':total_amount' => $total,
    ]);

    $orderId = (int)$pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO order_items
        (order_id, product_id, product_name, sku, quantity, unit_price, discount_percent, subtotal)
        VALUES (:order_id, :product_id, :product_name, :sku, :quantity, :unit_price, :discount_percent, :subtotal)");

    foreach ($itemsParaOrden as $item) {
        $stmtItem->execute([
            ':order_id' => $orderId,
            ':product_id' => $item['product_id'],
            ':product_name' => $item['product_name'],
            ':sku' => $item['sku'],
            ':quantity' => $item['quantity'],
            ':unit_price' => $item['unit_price'],
            ':discount_percent' => $item['discount_percent'],
            ':subtotal' => $item['subtotal'],
        ]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
}

$lineasCliente = [];
$lineasCliente[] = "Nuevo pedido desde la tienda online:";
if ($orderId) {
    $lineasCliente[] = "N° de pedido interno: #" . $orderId;
}
$lineasCliente[] = "";

if ($usuario) {
    $lineasCliente[] = "Cliente: " . ($usuario['name'] ?? '');
    if (!empty($usuario['email'])) {
        $lineasCliente[] = "Email: " . $usuario['email'];
    }
    if (!empty($usuario['phone'])) {
        $lineasCliente[] = "Teléfono: " . $usuario['phone'];
    }
    if (!empty($usuario['customer_type'])) {
        $lineasCliente[] = "Tipo de cliente: " . $usuario['customer_type'];
    }
} else {
    $lineasCliente[] = "Cliente sin sesión iniciada.";
    $lineasCliente[] = "Pedile sus datos de contacto para coordinar pago y entrega.";
}

$lineasCliente[] = "";
$lineasCliente[] = "Productos:";
$lineasCliente = array_merge($lineasCliente, $lineasProductos);
$lineasCliente[] = "";
$lineasCliente[] = "TOTAL: $" . number_format($total, 2, ',', '.');
$lineasCliente[] = "";
$lineasCliente[] = "Por favor, confirmar disponibilidad y coordinar pago/envío.";

$mensaje = implode("\n", $lineasCliente);

$phone = preg_replace('/\D+/', '', $ownerWhatsapp);
if (!$phone) {
    die('No está configurado el número de WhatsApp del dueño. Editá config.php ($ownerWhatsapp).');
}

$url = 'https://wa.me/' . $phone . '?text=' . urlencode($mensaje);

header('Location: ' . $url);
exit;

