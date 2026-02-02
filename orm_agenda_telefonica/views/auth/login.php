<?php
// views/auth/login.php
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Agenda Virtual</title>
    <link rel="stylesheet" href="public/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1>Agenda Telefónica Virtual</h1>
            <p>Gestión de contactos</p>
        </header>
        
        <div class="card">
            <h2>Iniciar Sesión</h2>
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger">
                    <i>⚠️</i>
                    <span><?php echo htmlspecialchars($errors['general']); ?></span>
                </div>
            <?php endif; ?>
            
            <form action="index.php?action=login" method="POST">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ingresa tu usuario" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="contraseña">Contraseña</label>
                    <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Ingresa tu contraseña" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i>🔐</i>
                        <span>Iniciar Sesión</span>
                    </button>
                </div>
            </form>
            
            <p class="text-center">¿No tienes cuenta? <a href="index.php?action=registro">Regístrate aquí</a></p>
        </div>
    </div>
    
    <script src="public/js/persona.js"></script>
</body>
</html>