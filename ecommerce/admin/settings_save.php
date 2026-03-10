<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/admin/settings.php');
    exit;
}

$storeName = trim($_POST['store_name'] ?? '');
$primaryColor = trim($_POST['primary_color'] ?? '#1d3557');
$secondaryColor = trim($_POST['secondary_color'] ?? '#457b9d');
$whatsapp = trim($_POST['whatsapp'] ?? '');
$contactEmail = trim($_POST['contact_email'] ?? '');
$contactPhone = trim($_POST['contact_phone'] ?? '');
$contactAddress = trim($_POST['contact_address'] ?? '');
$homeHeroTitle = trim($_POST['home_hero_title'] ?? '');
$homeHeroSubtitle = trim($_POST['home_hero_subtitle'] ?? '');

// Manejo de archivos (logo e imagen de portada)
$logoName = null;
$homeHeroImageName = null;

if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $logoName = 'logo.' . $ext;
        $dir = __DIR__ . '/../uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        move_uploaded_file($_FILES['logo']['tmp_name'], $dir . $logoName);
    }
}

if (!empty($_FILES['home_hero_image']['name']) && $_FILES['home_hero_image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['home_hero_image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $homeHeroImageName = 'home_hero.' . $ext;
        $dir = __DIR__ . '/../uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        move_uploaded_file($_FILES['home_hero_image']['tmp_name'], $dir . $homeHeroImageName);
    }
}

// Ver si ya existe registro de settings
$stmt = $pdo->query("SELECT id FROM settings LIMIT 1");
$existing = $stmt->fetch();

if ($existing) {
    $sql = "UPDATE settings SET
                store_name = :store_name,
                primary_color = :primary_color,
                secondary_color = :secondary_color,
                whatsapp = :whatsapp,
                contact_email = :contact_email,
                contact_phone = :contact_phone,
                contact_address = :contact_address,
                home_hero_title = :home_hero_title,
                home_hero_subtitle = :home_hero_subtitle";
    if ($logoName) {
        $sql .= ", logo = :logo";
    }
    if ($homeHeroImageName) {
        $sql .= ", home_hero_image = :home_hero_image";
    }
    $stmt = $pdo->prepare($sql . " WHERE id = :id");
    $stmt->bindValue(':id', $existing['id'], PDO::PARAM_INT);
} else {
    $stmt = $pdo->prepare("INSERT INTO settings
        (store_name, primary_color, secondary_color, whatsapp, contact_email, contact_phone,
         contact_address, home_hero_title, home_hero_subtitle, logo, home_hero_image, created_at)
        VALUES
        (:store_name, :primary_color, :secondary_color, :whatsapp, :contact_email, :contact_phone,
         :contact_address, :home_hero_title, :home_hero_subtitle, :logo, :home_hero_image, NOW())");
}

$stmt->bindValue(':store_name', $storeName);
$stmt->bindValue(':primary_color', $primaryColor);
$stmt->bindValue(':secondary_color', $secondaryColor);
$stmt->bindValue(':whatsapp', $whatsapp);
$stmt->bindValue(':contact_email', $contactEmail);
$stmt->bindValue(':contact_phone', $contactPhone);
$stmt->bindValue(':contact_address', $contactAddress);
$stmt->bindValue(':home_hero_title', $homeHeroTitle);
$stmt->bindValue(':home_hero_subtitle', $homeHeroSubtitle);

if (strpos($stmt->queryString, ':logo') !== false) {
    $stmt->bindValue(':logo', $logoName);
}
if (strpos($stmt->queryString, ':home_hero_image') !== false) {
    $stmt->bindValue(':home_hero_image', $homeHeroImageName);
}

$stmt->execute();

header('Location: ' . $baseUrl . '/admin/settings.php');
exit;

