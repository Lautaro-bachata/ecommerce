<?php
$registerError = $_SESSION['register_error'] ?? null;
unset($_SESSION['register_error']);
?>

<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-user-plus fa-4x text-primary"></i>
                </div>
                <h1 class="h3 fw-bold mb-2">Crear Cuenta</h1>
                <p class="text-muted">Registrate para empezar a comprar</p>
            </div>

            <?php if ($registerError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($registerError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="post" action="<?= $baseUrl ?>/user_register.php">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-user me-2 text-muted"></i>Nombre y Apellido
                            </label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="Juan Pérez" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-muted"></i>Correo Electrónico
                            </label>
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="tu@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">
                                <i class="fas fa-phone me-2 text-muted"></i>Teléfono / WhatsApp
                            </label>
                            <input type="text" id="phone" name="phone" class="form-control"
                                   placeholder="+54 9 11 1234-5678">
                        </div>

                        <div class="mb-3">
                            <label for="customer_type" class="form-label fw-semibold">
                                <i class="fas fa-tag me-2 text-muted"></i>Tipo de Cliente
                            </label>
                            <select id="customer_type" name="customer_type" class="form-select" required>
                                <option value="minorista">Minorista (compras al por menor)</option>
                                <option value="mayorista">Mayorista (compras al por mayor)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-muted"></i>Contraseña
                                </label>
                                <input type="password" id="password" name="password" class="form-control"
                                       placeholder="••••••••" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-muted"></i>Repetir
                                </label>
                                <input type="password" id="password_confirm" name="password_confirm" class="form-control"
                                       placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label small" for="terms">
                                Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> de uso del sitio
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i> Crear Cuenta
                        </button>

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                ¿Ya tenés cuenta?
                                <a href="<?= $baseUrl ?>/index.php?page=login" class="text-decoration-none fw-semibold">
                                    Iniciá sesión aquí
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

