<?php
$loginError = $_SESSION['login_error'] ?? null;
$loginSuccess = $_SESSION['login_success'] ?? null;
unset($_SESSION['login_error'], $_SESSION['login_success']);
?>

<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                </div>
                <h1 class="h3 fw-bold mb-2">Iniciar Sesión</h1>
                <p class="text-muted">Accedé a tu cuenta para continuar</p>
            </div>

            <?php if ($loginError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($loginError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($loginSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($loginSuccess) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="post" action="<?= $baseUrl ?>/user_login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-muted"></i>Correo Electrónico
                            </label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg"
                                   placeholder="tu@email.com" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2 text-muted"></i>Contraseña
                            </label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg"
                                   placeholder="••••••••" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">
                                    Recordarme
                                </label>
                            </div>
                            <a href="#" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Ingresar
                        </button>

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                ¿No tenés cuenta?
                                <a href="<?= $baseUrl ?>/index.php?page=registro" class="text-decoration-none fw-semibold">
                                    Registrate aquí
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    <i class="fas fa-shield-alt me-1"></i>
                    Tus datos están protegidos y seguros
                </p>
            </div>
        </div>
    </div>
</div>

