# Ecommerce para minoristas y mayoristas (PHP/MySQL)

Proyecto de tienda online profesional y panel de gestión para comercios minoristas y mayoristas. Incluye:

- Sitio público con inicio, nosotros, catálogo, ofertas, carrito, login/registro y panel de usuario.
- Carrito con envío de pedido por WhatsApp al dueño del comercio.
- Panel de administración con gestión de productos, stock, caja, gastos, proveedores, estadísticas y personalización del sitio.

## 1. Requisitos

- PHP 8.x (funciona en XAMPP y Hostinger).
- MySQL 5.7+ / MariaDB.
- Extensión PDO MySQL habilitada.

## 2. Instalación en XAMPP (local)

1. Copiar la carpeta del proyecto en:
   - `c:\xampp\htdocs\ecommerce`
2. Crear la base de datos:
   - Abrir phpMyAdmin (`http://localhost/phpmyadmin`).
   - Importar el archivo `database.sql` incluido en la raíz del proyecto.
3. Ajustar la configuración en `config.php`:
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` según tu entorno.
   - `$baseUrl` (por ejemplo `/ecommerce` en local).
   - `$ownerWhatsapp` con tu número real (solo números, con código de país, sin `+`, espacios ni guiones).
4. Acceder al sitio:
   - Front público: `http://localhost/ecommerce/index.php`
   - Panel admin: `http://localhost/ecommerce/admin/login.php`

## 3. Instalación en Hostinger

1. Subir todos los archivos del proyecto al hosting (carpeta `public_html` o un subdirectorio, por ejemplo `public_html/ecommerce`).
2. Crear una base de datos MySQL desde el panel de Hostinger y anotar:
   - Nombre de BD, usuario, contraseña y host.
3. Importar `database.sql` desde phpMyAdmin de Hostinger.
4. Editar `config.php` en el servidor:
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` con los datos de Hostinger.
   - `$baseUrl` con la ruta pública (ej: `''` si está en la raíz, o `/ecommerce` si va en subcarpeta).
   - `$ownerWhatsapp` con el número real de WhatsApp que recibirá los pedidos.
5. Verificar permisos de carpetas para subir imágenes:
   - `uploads/` y `uploads/products/` deben permitir escritura (chmod 755/775 según el servidor).

## 4. Accesos iniciales

### Panel de administración

- URL: `/admin/login.php`
- Email: `admin@tu-dominio.com`
- Contraseña: `Admin123`

Se recomienda cambiar este usuario/contraseña desde la base de datos o crear otro admin.

### Sitio público

- URL principal: `/index.php`
- Catálogo: `?page=catalogo`
- Ofertas: `?page=ofertas`
- Carrito: `?page=carrito`
- Login: `?page=login`
- Registro: `?page=registro`

## 5. Flujo de compra y pedido por WhatsApp

1. El cliente navega el catálogo y agrega productos al carrito.
2. Desde el carrito, hace clic en **“Finalizar pedido por WhatsApp”**.
3. El sistema:
   - Calcula subtotales y total.
   - Guarda un registro del pedido en las tablas `orders` y `order_items`.
   - Abre WhatsApp (web o app) con un mensaje listo que incluye:
     - Datos del cliente (si está logueado).
     - Número interno de pedido.
     - Listado detallado de productos y cantidades.
     - Total del pedido.
4. El dueño responde desde WhatsApp y coordina pago y entrega.

## 6. Panel de administración (resumen)

- **Dashboard**: visión general de ventas del día, caja del día y productos con stock bajo.
- **Productos**:
  - Alta/edición/baja lógica (activar/desactivar).
  - Campos: nombre, descripción, SKU, categoría, precio minorista, precio mayorista, descuento %, stock actual, stock mínimo y foto.
  - Carga de imágenes a `uploads/products/`.
- **Stock**:
  - Listado de productos con filtro de stock bajo.
  - Registro de movimientos de stock (ingresos/egresos) con fecha, cantidad y motivo.
  - Actualización automática del stock del producto.
- **Caja y gastos**:
  - Registro de ingresos (ventas, otros) y gastos (proveedores, servicios, alquiler, etc.).
  - Resumen diario, semanal y mensual con ingresos, gastos y balance.
  - Listado de gastos recientes.
- **Proveedores**:
  - ABM de proveedores con datos de contacto y condiciones comerciales.
- **Estadísticas**:
  - Ventas por día (últimos 14 días).
  - Ventas por categoría de producto.
- **Personalización del sitio** (`settings`):
  - Nombre del comercio.
  - Colores principal y secundario (afectan botones, enlaces y navbar).
  - Logo e imagen de portada.
  - WhatsApp de pedidos, email, teléfono y dirección.
  - Textos de la sección de portada (título y frase principal).

## 7. Personalización visual

Desde **Administración → Diseño y ajustes** se puede:

- Cambiar el nombre del comercio (se refleja en el logo de la navbar y el pie de página).
- Definir colores corporativos que se aplican al header, botones y enlaces.
- Subir un logo y una imagen de portada.
- Definir el título y subtítulo del banner principal de la home.
- Configurar los datos de contacto (WhatsApp, email, teléfono, dirección) que aparecen en diversos lugares del sitio.

## 8. Seguridad y buenas prácticas

- Contraseñas de usuarios y administradores almacenadas con `password_hash()`.
- Acceso al panel admin protegido por login y sesión (`admins`).
- Panel de usuario solo accesible si el cliente está logueado.
- Preparado para usar HTTPS en producción (recomendado en Hostinger).

## 9. Próximas mejoras sugeridas (opcionales)

El sistema está listo para uso real, pero se pueden incorporar futuras mejoras sin romper la estructura actual:

- Recuperación de contraseña por email.
- Diferentes roles de administrador (dueño, empleado, etc.).
- Exportación de reportes a Excel/CSV.
- Estados de pedido más detallados y gestión visual de su avance.

