<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php
$error = \App\Core\Session::flash('error');
$success = \App\Core\Session::flash('success');
if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-header text-white">
        <h4>Perfil de Usuario</h4>
    </div>
    <div class="card-body">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($user['role']) ?></p>
        <br>
        <a href="index.php?route=logout" class="btn btn-danger">Cerrar Sesión</a>
        <?php if ($user['role'] === 'admin'): ?>
            <a href="index.php?route=admin/sessions" class="btn btn-primary">Ver Sesiones Activas</a>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>