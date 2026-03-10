<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/index.php?page=registro');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$customerType = $_POST['customer_type'] ?? 'minorista';
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';
$terms = isset($_POST['terms']);

function redirectWithRegisterError(string $message): void
{
    $_SESSION['register_error'] = $message;
    header('Location: ' . $GLOBALS['baseUrl'] . '/index.php?page=registro');
    exit;
}

if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '') {
    redirectWithRegisterError('Por favor, completá todos los campos obligatorios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithRegisterError('El correo electrónico no es válido.');
}

if (strlen($password) < 6) {
    redirectWithRegisterError('La contraseña debe tener al menos 6 caracteres.');
}

if ($password !== $passwordConfirm) {
    redirectWithRegisterError('Las contraseñas no coinciden.');
}

if (!$terms) {
    redirectWithRegisterError('Debés aceptar los términos y condiciones.');
}

if (!in_array($customerType, ['minorista', 'mayorista'], true)) {
    $customerType = 'minorista';
}

$pdo = getPDO();

// Verificar si el email ya existe
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    redirectWithRegisterError('Ya existe una cuenta registrada con ese correo electrónico.');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users
    (name, email, password_hash, phone, customer_type, is_active, created_at)
    VALUES (:name, :email, :password_hash, :phone, :customer_type, 1, NOW())");
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':password_hash' => $passwordHash,
    ':phone' => $phone,
    ':customer_type' => $customerType,
]);

$userId = (int)$pdo->lastInsertId();

// Iniciar sesión automáticamente
$_SESSION['user'] = [
    'id' => $userId,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'customer_type' => $customerType,
];

$_SESSION['login_success'] = 'Tu cuenta fue creada con éxito. ¡Bienvenido/a!';

header('Location: ' . $baseUrl . '/index.php?page=panel-usuario');
exit;

