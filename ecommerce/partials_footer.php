    </div>
</main>
<footer class="text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-store me-2"></i><?= htmlspecialchars($storeName ?? 'Mi Tienda') ?>
                </h5>
                <p class="text-muted">Tu tienda de confianza para compras online seguras y convenientes.</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-light"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-whatsapp fa-lg"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-link me-2"></i>Enlaces Rápidos
                </h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-muted text-decoration-none">Inicio</a></li>
                    <li class="mb-2"><a href="index.php?page=catalogo" class="text-muted text-decoration-none">Catálogo</a></li>
                    <li class="mb-2"><a href="index.php?page=ofertas" class="text-muted text-decoration-none">Ofertas</a></li>
                    <li class="mb-2"><a href="index.php?page=nosotros" class="text-muted text-decoration-none">Nosotros</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-envelope me-2"></i>Contacto
                </h5>
                <?php if (!empty($settings['contact_phone']) || !empty($settings['contact_email'])): ?>
                    <div class="text-muted">
                        <?php if (!empty($settings['contact_phone'])): ?>
                            <p class="mb-2">
                                <i class="fas fa-phone me-2"></i>
                                <?= htmlspecialchars($settings['contact_phone']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($settings['contact_email'])): ?>
                            <p class="mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <?= htmlspecialchars($settings['contact_email']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Desarrollado para comercios minoristas y mayoristas.
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="text-center">
            <small class="text-muted">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($storeName ?? 'Mi Tienda') ?>. Todos los derechos reservados.
                <br>
                Desarrollado con <i class="fas fa-heart text-danger"></i> para tu negocio
            </small>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $baseUrl ?>/assets/js/main.js"></script>
</body>
</html>

