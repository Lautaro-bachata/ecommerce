<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/admin/cash.php');
    exit;
}

$pdo = getPDO();

$type = $_POST['type'] ?? 'ingreso';
$movementDate = $_POST['movement_date'] ?? date('Y-m-d');
$amount = (float)($_POST['amount'] ?? 0);
$category = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!in_array($type, ['ingreso', 'egreso'], true) || $amount <= 0) {
    header('Location: ' . $baseUrl . '/admin/cash.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO cash_movements
    (movement_date, type, amount, category, description, created_by_admin_id, created_at)
    VALUES (:movement_date, :type, :amount, :category, :description, :admin_id, NOW())");
$stmt->execute([
    ':movement_date' => $movementDate,
    ':type' => $type,
    ':amount' => $amount,
    ':category' => $category,
    ':description' => $description,
    ':admin_id' => $_SESSION['admin']['id'] ?? null,
]);

header('Location: ' . $baseUrl . '/admin/cash.php');
exit;

