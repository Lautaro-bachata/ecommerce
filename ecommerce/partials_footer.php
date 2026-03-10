    </div>
</main>
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 mb-4">
                <h5 class="d-flex align-items-center mb-3">
                    <?php if ($logo): ?>
                        <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($storeName ?? 'Mi Tienda') ?>" height="30" class="me-2">
                    <?php else: ?>
                        <i class="fas fa-store me-2"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($storeName ?? 'Mi Tienda') ?>
                </h5>
                <p class="mb-4">Tu tienda de confianza para compras online seguras y convenientes. Calidad, variedad y los mejores precios.</p>
                <div class="social-links d-flex gap-2">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <?php if (!empty($settings['whatsapp'])): ?>
                        <a href="https://wa.me/<?= preg_replace('/\D+/', '', $settings['whatsapp']) ?>" title="WhatsApp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-2 col-6 mb-4">
                <h5 class="mb-3">Navegación</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?= $baseUrl ?>/index.php">Inicio</a></li>
                    <li class="mb-2"><a href="<?= $baseUrl ?>/index.php?page=catalogo">Catálogo</a></li>
                    <li class="mb-2"><a href="<?= $baseUrl ?>/index.php?page=ofertas">Ofertas</a></li>
                    <li class="mb-2"><a href="<?= $baseUrl ?>/index.php?page=nosotros">Nosotros</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <h5 class="mb-3">Información</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">Preguntas Frecuentes</a></li>
                    <li class="mb-2"><a href="#">Términos y Condiciones</a></li>
                    <li class="mb-2"><a href="#">Política de Privacidad</a></li>
                    <li class="mb-2"><a href="#">Métodos de Pago</a></li>
                </ul>
            </div>
            <div class="col-lg-3 mb-4">
                <h5 class="mb-3">Contacto</h5>
                <ul class="list-unstyled">
                    <?php if (!empty($settings['contact_phone'])): ?>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:<?= htmlspecialchars($settings['contact_phone']) ?>">
                                <?= htmlspecialchars($settings['contact_phone']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($settings['contact_email'])): ?>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>">
                                <?= htmlspecialchars($settings['contact_email']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($settings['contact_address'])): ?>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?= htmlspecialchars($settings['contact_address']) ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($settings['whatsapp'])): ?>
                        <li class="mb-2">
                            <i class="fab fa-whatsapp me-2"></i>
                            <a href="https://wa.me/<?= preg_replace('/\D+/', '', $settings['whatsapp']) ?>" target="_blank">
                                WhatsApp
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <hr class="my-4 opacity-25">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="opacity-75">
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($storeName ?? 'Mi Tienda') ?>. Todos los derechos reservados.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="opacity-75">
                    Desarrollado con <i class="fas fa-heart text-danger"></i> para tu negocio
                </small>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $baseUrl ?>/assets/js/main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
});
</script>
</body>
</html>

