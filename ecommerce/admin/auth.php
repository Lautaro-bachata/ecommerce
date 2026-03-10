<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin']);
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: ' . $GLOBALS['baseUrl'] . '/index.php?page=login');
        exit;
    }
}

