<?php
$loginError = $_SESSION['login_error'] ?? null;
$loginSuccess = $_SESSION['login_success'] ?? null;
unset($_SESSION['login_error'], $_SESSION['login_success']);
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-5">
        <h1 class="h3 mb-3 text-center">Iniciar sesión</h1>
        <?php if ($loginError): ?>
            <div class="alert alert-danger auto-dismiss-alert">
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php elseif ($loginSuccess): ?>
            <div class="alert alert-success auto-dismiss-alert">
                <?= htmlspecialchars($loginSuccess) ?>
            </div>
        <?php endif; ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= $baseUrl ?>/user_login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>
                        <a href="#" class="small text-muted">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        Ingresar
                    </button>
                    <p class="small text-center mb-0">
                        ¿No tenés cuenta?
                        <a href="<?= $baseUrl ?>/index.php?page=registro">Registrate</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

