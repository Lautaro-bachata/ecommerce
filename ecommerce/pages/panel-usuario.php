<?php
$usuario = $_SESSION['user'] ?? null;
if (!$usuario) {
    header('Location: ' . $baseUrl . '/index.php?page=login');
    exit;
}
?>

<div class="mt-3 mb-3">
    <h1 class="h3">Panel de usuario</h1>
    <p class="text-muted">
        Bienvenido/a, <?= htmlspecialchars($usuario['name']) ?>.
        Desde aquí vas a poder ver tus pedidos, actualizar tus datos y gestionar tus direcciones.
    </p>
</div>

<?php if ($usuario): ?>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Mis datos</h5>
                    <p class="small text-muted">
                        Información básica de tu cuenta.
                    </p>
                    <ul class="list-unstyled small mb-3">
                        <li><strong>Nombre:</strong> <?= htmlspecialchars($usuario['name']) ?></li>
                        <li><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></li>
                        <li><strong>Tipo de cliente:</strong> <?= htmlspecialchars($usuario['customer_type']) ?></li>
                    </ul>
                    <a href="#" class="btn btn-sm btn-outline-primary">Editar datos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Mis pedidos</h5>
                    <p class="small text-muted">
                        Consulta el historial de tus compras y el estado de tus pedidos.
                    </p>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Ver pedidos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Direcciones y facturación</h5>
                    <p class="small text-muted">
                        Gestioná tus direcciones de envío y datos de facturación.
                    </p>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Gestionar direcciones</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

