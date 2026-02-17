<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    \App\Core\Session::start();
    error_log("Session started in register.php");
}
$csrf_token = \App\Core\CSRF::generate();
error_log("CSRF generated in register.php: $csrf_token");
require __DIR__ . '/../layouts/header.php';?>

<h3>Sesiones Activas</h3>

<?php
$error = \App\Core\Session::flash('error');
$success = \App\Core\Session::flash('success');
if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (empty($sessions)): ?>
    <p>No hay sesiones activas.</p>
<?php else: ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Sesión</th>
                <th>Usuario</th>
                <th>IP</th>
                <th>Última Actividad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($sessions as $session): ?>
            <tr>
                <td><?= htmlspecialchars($session['session_id']) ?></td>
                <td><?= htmlspecialchars($session['username']) ?></td>
                <td><?= htmlspecialchars($session['ip_address']) ?></td>
                <td><?= htmlspecialchars($session['last_activity']) ?></td>
                <td>
                    <a href="index.php?route=admin/revoke-session&session=<?= htmlspecialchars($session['session_id']) ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Estás seguro de revocar esta sesión?')">
                        Revocar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="index.php?route=profile" class="btn btn-secondary">Volver al Perfil</a>

<?php require __DIR__ . '/../layouts/footer.php'; ?>