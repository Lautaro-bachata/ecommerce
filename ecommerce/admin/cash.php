<?php
require_once __DIR__ . '/partials_header.php';
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

$tab = $_GET['tab'] ?? 'hoy'; // hoy | gastos | resumen

$hoy = date('Y-m-d');

// Movimientos del día
$stmt = $pdo->prepare("SELECT * FROM cash_movements WHERE DATE(movement_date) = :hoy ORDER BY movement_date DESC, id DESC");
$stmt->execute([':hoy' => $hoy]);
$movimientosHoy = $stmt->fetchAll();

// Resumen diario/semanal/mensual
function resumenPeriodo(PDO $pdo, string $desde, string $hasta): array {
    $stmt = $pdo->prepare("
        SELECT
          COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount ELSE 0 END),0) AS ingresos,
          COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount ELSE 0 END),0) AS egresos
        FROM cash_movements
        WHERE movement_date BETWEEN :desde AND :hasta
    ");
    $stmt->execute([':desde' => $desde, ':hasta' => $hasta]);
    $row = $stmt->fetch();
    return [
        'ingresos' => (float)$row['ingresos'],
        'egresos' => (float)$row['egresos'],
        'balance' => (float)$row['ingresos'] - (float)$row['egresos'],
    ];
}

$inicioSemana = date('Y-m-d', strtotime('monday this week'));
$finSemana = date('Y-m-d', strtotime('sunday this week'));
$inicioMes = date('Y-m-01');
$finMes = date('Y-m-t');

$resumenDia = resumenPeriodo($pdo, $hoy, $hoy);
$resumenSemana = resumenPeriodo($pdo, $inicioSemana, $finSemana);
$resumenMes = resumenPeriodo($pdo, $inicioMes, $finMes);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Caja y movimientos</h1>
    <div class="btn-group">
        <a href="<?= $baseUrl ?>/admin/cash_movement_form.php?type=ingreso" class="btn btn-sm btn-success">Nuevo ingreso</a>
        <a href="<?= $baseUrl ?>/admin/cash_movement_form.php?type=egreso" class="btn btn-sm btn-danger">Nuevo gasto</a>
    </div>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'hoy' ? 'active' : '' ?>" href="<?= $baseUrl ?>/admin/cash.php?tab=hoy">Movimientos de hoy</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'gastos' ? 'active' : '' ?>" href="<?= $baseUrl ?>/admin/cash.php?tab=gastos">Listado de gastos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'resumen' ? 'active' : '' ?>" href="<?= $baseUrl ?>/admin/cash.php?tab=resumen">Resumen</a>
    </li>
</ul>

<?php if ($tab === 'hoy'): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Resumen de hoy (<?= date('d/m/Y') ?>)</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="small text-muted">Ingresos</div>
                    <div class="h5 text-success">$<?= number_format($resumenDia['ingresos'], 2, ',', '.') ?></div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">Gastos</div>
                    <div class="h5 text-danger">$<?= number_format($resumenDia['egresos'], 2, ',', '.') ?></div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">Balance del día</div>
                    <div class="h5"><?= number_format($resumenDia['balance'], 2, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Movimientos de caja de hoy</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                    <tr>
                        <th>Fecha/hora</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th class="text-end">Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$movimientosHoy): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Sin movimientos registrados hoy.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($movimientosHoy as $m): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($m['movement_date'])) ?></td>
                                <td>
                                    <?php if ($m['type'] === 'ingreso'): ?>
                                        <span class="badge bg-success">Ingreso</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Gasto</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($m['category'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($m['description'] ?? '') ?></td>
                                <td class="text-end">
                                    <?php if ($m['type'] === 'ingreso'): ?>
                                        <span class="text-success">+ $<?= number_format($m['amount'], 2, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">- $<?= number_format($m['amount'], 2, ',', '.') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($tab === 'gastos'): ?>
    <?php
    $stmt = $pdo->query("SELECT * FROM cash_movements WHERE type = 'egreso' ORDER BY movement_date DESC, id DESC LIMIT 200");
    $gastos = $stmt->fetchAll();
    ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Últimos gastos registrados</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th class="text-end">Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$gastos): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Sin gastos registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($gastos as $m): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($m['movement_date'])) ?></td>
                                <td><?= htmlspecialchars($m['category'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($m['description'] ?? '') ?></td>
                                <td class="text-end text-danger">
                                    - $<?= number_format($m['amount'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($tab === 'resumen'): ?>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Hoy</h5>
                    <p class="small text-muted">Resumen de ingresos y gastos del día actual.</p>
                    <ul class="list-unstyled small mb-0">
                        <li><strong>Ingresos:</strong> $<?= number_format($resumenDia['ingresos'], 2, ',', '.') ?></li>
                        <li><strong>Gastos:</strong> $<?= number_format($resumenDia['egresos'], 2, ',', '.') ?></li>
                        <li><strong>Balance:</strong> $<?= number_format($resumenDia['balance'], 2, ',', '.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Semana actual</h5>
                    <p class="small text-muted">Desde <?= date('d/m', strtotime($inicioSemana)) ?> hasta <?= date('d/m', strtotime($finSemana)) ?>.</p>
                    <ul class="list-unstyled small mb-0">
                        <li><strong>Ingresos:</strong> $<?= number_format($resumenSemana['ingresos'], 2, ',', '.') ?></li>
                        <li><strong>Gastos:</strong> $<?= number_format($resumenSemana['egresos'], 2, ',', '.') ?></li>
                        <li><strong>Balance:</strong> $<?= number_format($resumenSemana['balance'], 2, ',', '.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Mes actual</h5>
                    <p class="small text-muted">Desde <?= date('d/m', strtotime($inicioMes)) ?> hasta <?= date('d/m', strtotime($finMes)) ?>.</p>
                    <ul class="list-unstyled small mb-0">
                        <li><strong>Ingresos:</strong> $<?= number_format($resumenMes['ingresos'], 2, ',', '.') ?></li>
                        <li><strong>Gastos:</strong> $<?= number_format($resumenMes['egresos'], 2, ',', '.') ?></li>
                        <li><strong>Balance:</strong> $<?= number_format($resumenMes['balance'], 2, ',', '.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials_footer.php'; ?>

