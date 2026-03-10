<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/index.php?page=login');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

function redirectWithLoginError(string $message): void
{
    $_SESSION['login_error'] = $message;
    header('Location: ' . $GLOBALS['baseUrl'] . '/index.php?page=login');
    exit;
}

if ($email === '' || $password === '') {
    redirectWithLoginError('Completá tu email y contraseña.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithLoginError('El correo electrónico no es válido.');
}

$pdo = getPDO();

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    redirectWithLoginError('Email o contraseña incorrectos.');
}

$_SESSION['user'] = [
    'id' => (int)$user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'phone' => $user['phone'],
    'customer_type' => $user['customer_type'],
];

$_SESSION['login_success'] = 'Inicio de sesión correcto.';

header('Location: ' . $baseUrl . '/index.php?page=panel-usuario');
exit;

