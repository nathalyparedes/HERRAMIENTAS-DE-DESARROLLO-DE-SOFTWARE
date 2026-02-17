<?php
declare(strict_types=1);

ob_start(); // Añade esto para buffering de output

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Session;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;

try {
    Session::start();

    $route = $_GET['route'] ?? 'login';

    $authController = new AuthController();
    $adminController = new AdminController();

    switch ($route) {
        case 'login':
            AuthMiddleware::guestOnly();
            $authController->login();
            break;

        case 'register':
            AuthMiddleware::guestOnly();
            $authController->register();
            break;

        case 'logout':
            AuthMiddleware::userOnly();
            $authController->logout();
            break;

        case 'profile':
            AuthMiddleware::userOnly();
            $authController->profile();
            break;

        case 'admin/sessions':
            AuthMiddleware::adminOnly();
            $adminController->sessions();
            break;

        case 'admin/users':
            AuthMiddleware::adminOnly();
            $adminController->users();
            break;

        case 'admin/revoke-session':
            AuthMiddleware::adminOnly();
            $adminController->revokeSession();
            break;

        case 'admin/delete-user':
            AuthMiddleware::adminOnly();
            $adminController->deleteUser();
            break;

        default:
            http_response_code(404);
            require __DIR__ . '/../app/Views/errors/404.php';
            break;
    }
} catch (Throwable $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Error interno del servidor</h1>";
}

ob_end_flush(); 