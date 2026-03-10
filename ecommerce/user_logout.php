<?php
session_start();
require_once __DIR__ . '/config.php';

unset($_SESSION['user']);

header('Location: ' . $baseUrl . '/index.php');
exit;

