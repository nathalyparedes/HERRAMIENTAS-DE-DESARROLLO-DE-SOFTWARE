<?php
namespace App\Middleware;

use App\Models\SessionModel;
use App\Core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        // Asegura que la sesión esté iniciada
        if (session_status() !== PHP_SESSION_ACTIVE) {
            Session::start();
        }

        if (!Session::isLoggedIn()) {
            header("Location: ?route=login");
            exit;
        }

        // Verificar que la sesión exista en BD
        $sessionModel = new SessionModel();
        if (!$sessionModel->exists(session_id())) {
            Session::destroy();
            Session::flash('error', 'Sesión inválida.');
            header("Location: ?route=login");
            exit;
        }

    }

    public static function adminOnly(): void
    {
        self::handle();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            Session::flash('error', 'Acceso denegado. Solo administradores.');
            header("Location: ?route=login");
            exit;
        }
    }

    public static function userOnly(): void
    {
        self::handle();
    }

    public static function guestOnly(): void
    {
        // Asegura que la sesión esté iniciada
        if (session_status() !== PHP_SESSION_ACTIVE) {
            Session::start();
        }

        if (Session::isLoggedIn()) {
            header("Location: ?route=profile");
            exit;
        }
    }
}