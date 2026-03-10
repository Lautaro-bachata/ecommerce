<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

// Configuración general del sitio (1 solo registro en settings)
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch() ?: [
    'store_name' => $storeName,
    'primary_color' => '#1d3557',
    'secondary_color' => '#457b9d',
    'whatsapp' => $ownerWhatsapp,
    'home_hero_title' => 'Ecommerce profesional para tu comercio',
    'home_hero_subtitle' => 'Vende al por menor y por mayor con un catálogo atractivo y administración centralizada.',
    'contact_email' => $ownerEmail,
    'contact_phone' => '',
    'contact_address' => '',
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Diseño y ajustes del sitio</h1>
</div>

<form method="post" action="<?= $baseUrl ?>/admin/settings_save.php" enctype="multipart/form-data">
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Identidad de la marca</h5>
                    <div class="mb-3">
                        <label class="form-label">Nombre del comercio</label>
                        <input type="text" name="store_name" class="form-control"
                               value="<?= htmlspecialchars($settings['store_name']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Color principal</label>
                            <input type="color" name="primary_color" class="form-control form-control-color"
                                   value="<?= htmlspecialchars($settings['primary_color']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Color secundario</label>
                            <input type="color" name="secondary_color" class="form-control form-control-color"
                                   value="<?= htmlspecialchars($settings['secondary_color']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo (opcional)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">Si subís un logo nuevo reemplazará al anterior.</small>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Datos de contacto</h5>
                    <div class="mb-3">
                        <label class="form-label">WhatsApp para pedidos</label>
                        <input type="text" name="whatsapp" class="form-control"
                               value="<?= htmlspecialchars($settings['whatsapp']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email de contacto</label>
                        <input type="email" name="contact_email" class="form-control"
                               value="<?= htmlspecialchars($settings['contact_email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="contact_phone" class="form-control"
                               value="<?= htmlspecialchars($settings['contact_phone']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea name="contact_address" rows="2" class="form-control"><?= htmlspecialchars($settings['contact_address']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Portada / Inicio</h5>
                    <div class="mb-3">
                        <label class="form-label">Título principal</label>
                        <input type="text" name="home_hero_title" class="form-control"
                               value="<?= htmlspecialchars($settings['home_hero_title']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtítulo / frase de venta</label>
                        <textarea name="home_hero_subtitle" rows="3" class="form-control"><?= htmlspecialchars($settings['home_hero_subtitle']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen de portada (opcional)</label>
                        <input type="file" name="home_hero_image" class="form-control" accept="image/*">
                        <small class="text-muted">Ideal una imagen horizontal que represente tu negocio.</small>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">
                Guardar configuración
            </button>
        </div>
    </div>
</form>

<?php require __DIR__ . '/partials_footer.php'; ?>

