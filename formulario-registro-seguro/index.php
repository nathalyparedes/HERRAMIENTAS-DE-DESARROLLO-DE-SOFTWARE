<?php
// index.php - Front Controller 

session_start();

// =======================
// Definición de rutas 
// =======================
define('ROOT', __DIR__);  
define('CONTROLLERS', ROOT . '/controllers');
define('MODELS', ROOT . '/models');
define('VIEWS', ROOT . '/views');
define('INCLUDES', ROOT . '/includes');
define('CONFIG', ROOT . '/config');

// =======================
// Autoload básico
// =======================
spl_autoload_register(function ($class) {
    $paths = [
        CONTROLLERS . '/' . $class . '.php',
        MODELS . '/' . $class . '.php'
    ];

    foreach ($paths as $file) {
        if (file_exists($file) && is_readable($file)) {
            require_once $file;
            return;
        }
    }
    // Agregado: Log si clase no se encuentra
    error_log("Clase no encontrada: $class");
});

// =======================
// Cargar helpers necesarios ( verifica que existan)
// =======================
if (!file_exists(INCLUDES . '/security.php')) {
    die('Archivo de seguridad no encontrado.');
}
require_once INCLUDES . '/security.php';

// =======================
// Router simple 
// =======================
$allowedActions = ['form', 'process', 'success'];  // Lista blanca para seguridad
$action = $_GET['action'] ?? 'form';

if (!in_array($action, $allowedActions)) {
    http_response_code(400);
    die('Acción no válida.');
}

try {
    $controller = new RegisterController();

    switch ($action) {
        case 'process':
            $controller->processRegistration();
            break;
        case 'success':
            $controller->showSuccess();
            break;
        case 'form':
        default:
            $controller->showForm();
            break;
    }
} catch (Exception $e) {
    error_log('Error en router: ' . $e->getMessage());
    http_response_code(500);
    die('Error interno del servidor.');
}