<?php
// Configuración básica del sitio y la base de datos

// Modo desarrollo: mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ajusta estos datos antes de subir a Hostinger
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// URL base del sitio (ajustar en producción, por ejemplo: https://tudominio.com)
$baseUrl = '/ecommerce';

// Nombre del comercio (se puede hacer editable desde admin más adelante)
$storeName = 'Mi Comercio Online';

// Email del dueño para recibir pedidos (opcional, se puede usar más adelante)
$ownerEmail = 'dueno@midominio.com';

// Número de WhatsApp del dueño para recibir pedidos.
// Usar solo números con código de país, sin signos (+, -, espacios). Ej: 5491144445555
$ownerWhatsapp = '5491112345678';

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

