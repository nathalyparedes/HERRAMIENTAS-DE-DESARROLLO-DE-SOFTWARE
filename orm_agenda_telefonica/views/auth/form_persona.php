<?php
// views/auth/form_persona.php
$persona = $persona ?? new Persona();
$action = $action ?? 'registro';
$errors = $errors ?? [];

// Usa el método getPrimaryKeyValue() o accede vía __get()
$isEdit = !empty($persona->getPrimaryKeyValue());

$title = $isEdit ? 'Editar Perfil' : 'Registro';
$buttonText = $isEdit ? 'Actualizar Perfil' : 'Crear Cuenta';
$linkText = $isEdit ? 'Volver a Contactos' : '¿Ya tienes cuenta?';
$linkUrl = $isEdit ? 'index.php?action=contactos' : 'index.php?action=login';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Agenda Virtual</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1> Agenda Telefónica</h1>
            <p><?php echo $isEdit ? 'Actualiza tu información personal' : 'Únete a nuestra plataforma'; ?></p>
        </header>
        
        <div class="card bg-white rounded-md shadow-md p-6">
            <h2 class="text-center mb-6"><?php echo $title; ?></h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error mb-4" role="alert">
                    <strong>⚠️ Se encontraron los siguientes errores:</strong>
                    <ul class="mt-2 pl-4">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="index.php?action=<?php echo $action; ?>" method="POST" novalidate>
                <div class="form-group">
                    <label for="nombre_persona" class="form-label">Nombre</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="nombre_persona"
                        name="nombre_persona"
                        placeholder="Ingresa tu nombre"
                        required
                        autofocus
                        value="<?php echo htmlspecialchars($persona->nombre_persona ?? ''); ?>"
                        aria-describedby="nombre_help">
                    <small id="nombre_help" class="form-help">Campo obligatorio.</small>
                </div>

                <div class="form-group">
                    <label for="apellido_persona" class="form-label">Apellido</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="apellido_persona"
                        name="apellido_persona"
                        placeholder="Ingresa tu apellido"
                        required
                        value="<?php echo htmlspecialchars($persona->apellido_persona ?? ''); ?>"
                        aria-describedby="apellido_help">
                    <small id="apellido_help" class="form-help">Campo obligatorio.</small>
                </div>

                <div class="form-group">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="usuario"
                        name="usuario"
                        placeholder="Elige un usuario único"
                        required
                        value="<?php echo htmlspecialchars($persona->usuario ?? ''); ?>"
                        aria-describedby="usuario_help">
                    <small id="usuario_help" class="form-help">Debe ser único y contener solo letras y números.</small>
                </div>

                <div class="form-group">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <input 
                        type="password"
                        class="form-control"
                        id="contraseña"
                        name="contraseña"
                        placeholder="Contraseña segura (mín. 6 caracteres)"
                        minlength="6"
                        <?php echo $isEdit ? '' : 'required'; ?>
                        aria-describedby="contraseña_help">
                    <small id="contraseña_help" class="form-help">
                        <?php echo $isEdit ? 'Deja vacío para mantener la contraseña actual.' : 'Mínimo 6 caracteres, incluye mayúsculas y números.'; ?>
                    </small>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">
                    <?php echo $isEdit ? '💾' : '👤'; ?> <?php echo $buttonText; ?>
                </button>
            </form>

            <p class="text-center mt-6">
                <?php if ($isEdit): ?>
                    <a href="<?php echo $linkUrl; ?>" class="text-muted">Volver a Contactos</a>
                <?php else: ?>
                    ¿Ya tienes cuenta? <a href="<?php echo $linkUrl; ?>" class="text-primary">Inicia Sesión</a>
                <?php endif; ?>
            </p>
        </div>
    </div>
    
    <script src="public/js/persona.js"></script>
</body>
</html>