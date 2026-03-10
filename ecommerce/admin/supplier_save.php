<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/admin/suppliers.php');
    exit;
}

$pdo = getPDO();

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$contactPerson = trim($_POST['contact_person'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$conditions = trim($_POST['conditions'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if ($name === '') {
    header('Location: ' . $baseUrl . '/admin/suppliers.php');
    exit;
}

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE suppliers
        SET name = :name,
            contact_person = :contact_person,
            phone = :phone,
            email = :email,
            conditions = :conditions,
            notes = :notes
        WHERE id = :id");
    $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':contact_person' => $contactPerson,
        ':phone' => $phone,
        ':email' => $email,
        ':conditions' => $conditions,
        ':notes' => $notes,
    ]);
} else {
    $stmt = $pdo->prepare("INSERT INTO suppliers
        (name, contact_person, phone, email, conditions, notes, created_at)
        VALUES (:name, :contact_person, :phone, :email, :conditions, :notes, NOW())");
    $stmt->execute([
        ':name' => $name,
        ':contact_person' => $contactPerson,
        ':phone' => $phone,
        ':email' => $email,
        ':conditions' => $conditions,
        ':notes' => $notes,
    ]);
}

header('Location: ' . $baseUrl . '/admin/suppliers.php');
exit;

