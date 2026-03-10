<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/admin/stock.php');
    exit;
}

$pdo = getPDO();

$productId = (int)($_POST['product_id'] ?? 0);
$type = $_POST['type'] ?? 'ingreso';
$quantity = (int)($_POST['quantity'] ?? 0);
$movementDate = $_POST['movement_date'] ?? date('Y-m-d');
$note = trim($_POST['note'] ?? '');

if ($productId <= 0 || $quantity <= 0 || !in_array($type, ['ingreso', 'egreso'], true)) {
    header('Location: ' . $baseUrl . '/admin/stock.php');
    exit;
}

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("INSERT INTO stock_movements
        (product_id, movement_date, type, quantity, note, created_by_admin_id, created_at)
        VALUES (:product_id, :movement_date, :type, :quantity, :note, :admin_id, NOW())");
    $stmt->execute([
        ':product_id' => $productId,
        ':movement_date' => $movementDate,
        ':type' => $type,
        ':quantity' => $quantity,
        ':note' => $note,
        ':admin_id' => $_SESSION['admin']['id'] ?? null,
    ]);

    // Actualizar stock del producto
    if ($type === 'ingreso') {
        $stmt = $pdo->prepare("UPDATE products
                               SET stock_quantity = stock_quantity + :qty
                               WHERE id = :id");
    } else {
        $stmt = $pdo->prepare("UPDATE products
                               SET stock_quantity = GREATEST(stock_quantity - :qty, 0)
                               WHERE id = :id");
    }
    $stmt->execute([
        ':qty' => $quantity,
        ':id' => $productId,
    ]);

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
}

header('Location: ' . $baseUrl . '/admin/stock.php');
exit;

