<?php
$registerError = $_SESSION['register_error'] ?? null;
unset($_SESSION['register_error']);
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6 col-lg-5">
        <h1 class="h3 mb-3 text-center">Registro de usuario</h1>
        <?php if ($registerError): ?>
            <div class="alert alert-danger auto-dismiss-alert">
                <?= htmlspecialchars($registerError) ?>
            </div>
        <?php endif; ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= $baseUrl ?>/user_register.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre y apellido / Razón social</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono / WhatsApp</label>
                        <input type="text" id="phone" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="customer_type" class="form-label">Tipo de cliente</label>
                        <select id="customer_type" name="customer_type" class="form-select" required>
                            <option value="minorista">Minorista</option>
                            <option value="mayorista">Mayorista</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirm" class="form-label">Repetir contraseña</label>
                            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label small" for="terms">
                            Acepto los términos y condiciones de uso del sitio.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        Crear cuenta
                    </button>
                    <p class="small text-center mb-0">
                        ¿Ya tenés cuenta?
                        <a href="<?= $baseUrl ?>/index.php?page=login">Iniciar sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

