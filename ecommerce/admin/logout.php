<?php
session_start();
require_once __DIR__ . '/../config.php';

unset($_SESSION['admin']);

header('Location: ' . $baseUrl . '/admin/login.php');
exit;

