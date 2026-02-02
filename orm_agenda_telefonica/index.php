<?php
// index.php (Punto de entrada con routing a controladores)
session_start();
require_once 'controllers/LoginController.php';
require_once 'controllers/ContactoController.php';

$action = $_GET['action'] ?? null;

// Si no hay action especificada, redirigir basado en sesión
if (!$action) {
    if (isset($_SESSION['persona_id'])) {
        header('Location: index.php?action=contactos');
        exit;
    } else {
        $action = 'login';
    }
}

switch ($action) {
    case 'login':
    case 'registro':
    case 'editar_persona':
    case 'logout':
        $controller = new LoginController();
        $controller->$action();
        break;

    case 'contactos':
        $controller = new ContactoController();
        $controller->index();
        break;

    case 'agregar_contacto':
        $controller = new ContactoController();
        $controller->agregar();
        break;

    case 'editar_contacto':  
        $controller = new ContactoController();
        $controller->editar();
        break;

    case 'eliminar_contacto':
        $controller = new ContactoController();
        $controller->eliminar();
        break;

    default:
        $controller = new LoginController();
        $controller->login();
        break;
}
?>