<?php
// views/contactos/form_contacto.php
$contacto = $contacto ?? new Contacto();
$action = $action ?? 'agregar_contacto';
$errors = $errors ?? [];

// USA el método mágico __get() en lugar de acceder a attributes directamente
$isEdit = !empty($contacto->getPrimaryKeyValue());
$title = $isEdit ? 'Editar Contacto' : 'Agregar Nuevo Contacto';
$buttonText = $isEdit ? 'Actualizar Contacto' : 'Agregar Contacto';
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
            <h1>Agenda Telefónica</h1>
            <p><?php echo $isEdit ? 'Edita la información del contacto' : 'Agrega un nuevo contacto a tu lista'; ?></p>
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
                <!-- Campo oculto para el ID en edición -->
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo $contacto->id_contacto; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nombre_contacto" class="form-label">Nombre</label>
                    <input 
                        type="text" 
                        id="nombre_contacto" 
                        name="nombre_contacto" 
                        class="form-control" 
                        placeholder="Nombre del contacto" 
                        required 
                        autofocus
                        value="<?php echo htmlspecialchars($contacto->nombre_contacto ?? ''); ?>"
                        aria-describedby="nombre_help">
                    <small id="nombre_help" class="form-help">Campo obligatorio.</small>
                </div>
                
                <div class="form-group">
                    <label for="apellido_contacto" class="form-label">Apellido</label>
                    <input 
                        type="text" 
                        id="apellido_contacto" 
                        name="apellido_contacto" 
                        class="form-control" 
                        placeholder="Apellido del contacto" 
                        required 
                        value="<?php echo htmlspecialchars($contacto->apellido_contacto ?? ''); ?>"
                        aria-describedby="apellido_help">
                    <small id="apellido_help" class="form-help">Campo obligatorio.</small>
                </div>
                
                <div class="form-group">
                    <label for="telefono_contacto" class="form-label">Teléfono</label>
                    <input 
                        type="tel" 
                        id="telefono_contacto" 
                        name="telefono_contacto" 
                        class="form-control" 
                        placeholder="Número de teléfono" 
                        required 
                        value="<?php echo htmlspecialchars($contacto->telefono_contacto ?? ''); ?>"
                        aria-describedby="telefono_help">
                    <small id="telefono_help" class="form-help">Campo obligatorio. Incluye código de área si es necesario.</small>
                </div>
                
                <div class="form-group">
                    <label for="email_contacto" class="form-label">Email (opcional)</label>
                    <input 
                        type="email" 
                        id="email_contacto" 
                        name="email_contacto" 
                        class="form-control" 
                        placeholder="correo@ejemplo.com" 
                        value="<?php echo htmlspecialchars($contacto->email_contacto ?? ''); ?>"
                        aria-describedby="email_help">
                    <small id="email_help" class="form-help">Opcional. Debe ser un email válido.</small>
                </div>
                
                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?php echo $isEdit ? '💾' : ''; ?> <?php echo $buttonText; ?>
                    </button>
                    <a href="index.php?action=contactos" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    
    <footer class="main-footer">
        <p>&copy; <?php echo date('Y'); ?> Agenda Telefónica - Sistema de gestión de contactos</p>
    </footer>
    
    <script src="public/js/contacto.js"></script>
</body>
</html>