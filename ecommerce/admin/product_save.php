<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/admin/products.php');
    exit;
}

$pdo = getPDO();

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$sku = trim($_POST['sku'] ?? '');
$categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
$isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
$price = (float)($_POST['price'] ?? 0);
$wholesalePrice = $_POST['wholesale_price'] !== '' ? (float)$_POST['wholesale_price'] : null;
$discountPercent = (int)($_POST['discount_percent'] ?? 0);
$stockQuantity = (int)($_POST['stock_quantity'] ?? 0);
$stockMinAlert = (int)($_POST['stock_min_alert'] ?? 0);

if ($name === '' || $price <= 0) {
    header('Location: ' . $baseUrl . '/admin/products.php');
    exit;
}

$imageName = null;

if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        header('Location: ' . $baseUrl . '/admin/products.php');
        exit;
    }
    $imageName = uniqid('prod_') . '.' . $ext;
    $uploadDir = __DIR__ . '/../uploads/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
}

if ($id > 0) {
    $sql = "UPDATE products
            SET name = :name,
                description = :description,
                sku = :sku,
                category_id = :category_id,
                is_active = :is_active,
                price = :price,
                wholesale_price = :wholesale_price,
                discount_percent = :discount_percent,
                stock_quantity = :stock_quantity,
                stock_min_alert = :stock_min_alert";
    if ($imageName) {
        $sql .= ", image = :image";
    }
    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
} else {
    $sql = "INSERT INTO products
            (name, description, sku, category_id, is_active, price, wholesale_price,
             discount_percent, stock_quantity, stock_min_alert, image, created_at)
            VALUES
            (:name, :description, :sku, :category_id, :is_active, :price, :wholesale_price,
             :discount_percent, :stock_quantity, :stock_min_alert, :image, NOW())";
    $stmt = $pdo->prepare($sql);
}

$stmt->bindValue(':name', $name);
$stmt->bindValue(':description', $description);
$stmt->bindValue(':sku', $sku);
$stmt->bindValue(':category_id', $categoryId, $categoryId ? PDO::PARAM_INT : PDO::PARAM_NULL);
$stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);
$stmt->bindValue(':price', $price);
if ($wholesalePrice !== null) {
    $stmt->bindValue(':wholesale_price', $wholesalePrice);
} else {
    $stmt->bindValue(':wholesale_price', null, PDO::PARAM_NULL);
}
$stmt->bindValue(':discount_percent', $discountPercent, PDO::PARAM_INT);
$stmt->bindValue(':stock_quantity', $stockQuantity, PDO::PARAM_INT);
$stmt->bindValue(':stock_min_alert', $stockMinAlert, PDO::PARAM_INT);

if (strpos($sql, ':image') !== false) {
    $stmt->bindValue(':image', $imageName);
}

$stmt->execute();

header('Location: ' . $baseUrl . '/admin/products.php');
exit;

