<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ' . $baseUrl . '/admin/products.php');
    exit;
}

$pdo = getPDO();

$stmt = $pdo->prepare("UPDATE products SET is_active = IF(is_active = 1, 0, 1) WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: ' . $baseUrl . '/admin/products.php');
exit;

