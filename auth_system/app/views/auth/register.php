<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php
// Genera el token CSRF aquí para consistencia
$csrf_token = \App\Core\CSRF::generate();
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header text-center text-white">
                <h4>Registro de Usuario</h4>
            </div>
            <div class="card-body">
                <?php
                $error = \App\Core\Session::flash('error');
                $success = \App\Core\Session::flash('success');
                if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?route=register">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf_token) ?>">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="username" class="form-control" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required minlength="8" autocomplete="current-password">
                    </div>
                    <button class="btn  btn-primary w-100">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>