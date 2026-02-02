<?php
// views/contactos/contactos.php
$contactos = $contactos ?? [];
$contacto = $contacto ?? new stdClass();  
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contactos - Agenda Virtual</title>
    <link href="public/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
    

    <main class="container">

    <header class="main-header">
        <h1>Agenda Telefónica</h1>
        <p>Gestión de contactos</p>
    </header>
        <section class="contacts-section">
            <h2>Mis contactos</h2>
            <p class="contact-count"><?php echo count($contactos); ?> contactos registrados</p>

            <nav class="main-nav">
                <a href="index.php?action=editar_persona" class="btn btn-secondary" aria-label="Editar mi perfil">
                    Mi Perfil
                </a>
                <a href="index.php?action=logout" class="btn btn-danger" aria-label="Cerrar sesión">
                    Salir
                </a>
            </nav>

            <div class="mt-6 mb-4">
                <a href="index.php?action=agregar_contacto" class="btn btn-success btn-block" aria-label="Agregar nuevo contacto">
                     Nuevo contacto
                </a>
            </div>

            <?php if (!empty($contactos)): ?>
                <div class="table-responsive">
                    <table class="table" aria-label="Lista de contactos">
                        <thead>
                            <tr>
                                <th scope="col">Contacto</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Email</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contactos as $contacto): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($contacto->nombre_contacto . ' ' . $contacto->apellido_contacto); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($contacto->telefono_contacto); ?></td>
                                    <td><?php echo htmlspecialchars($contacto->email_contacto ?? '—'); ?></td>
                                    <td class="text-center">
                                        <div class="table-actions">
                                            <a href="index.php?action=editar_contacto&id=<?php echo $contacto->id_contacto; ?>" 
                                               class="btn btn-primary" title="Editar contacto" aria-label="Editar contacto <?php echo htmlspecialchars($contacto->nombre_contacto); ?>">
                                                Editar
                                            </a>
                                            <a href="index.php?action=eliminar_contacto&id=<?php echo $contacto->id_contacto; ?>" 
                                               class="btn btn-danger" title="Eliminar contacto" aria-label="Eliminar contacto <?php echo htmlspecialchars($contacto->nombre_contacto); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este contacto?');">
                                                Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert" role="alert">
                    📝 <strong>No tienes contactos aún.</strong> Agrega tu primer contacto usando el botón arriba.
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="main-footer">
        <p>&copy; <?php echo date('Y'); ?> Agenda Telefónica - Sistema de gestión de contactos</p>
    </footer>
    
    <script src="public/js/contacto.js"></script>
</body>
</html>