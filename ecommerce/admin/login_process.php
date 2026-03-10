<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/admin/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: ' . $baseUrl . '/admin/login.php?error=' . urlencode('Completa todos los campos.'));
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM admins WHERE email = :email AND is_active = 1 LIMIT 1");
$stmt->execute([':email' => $email]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    header('Location: ' . $baseUrl . '/admin/login.php?error=' . urlencode('Credenciales inválidas.'));
    exit;
}

$_SESSION['admin'] = [
    'id' => $admin['id'],
    'name' => $admin['name'],
    'email' => $admin['email'],
];

header('Location: ' . $baseUrl . '/admin/index.php');
exit;

