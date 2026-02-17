<?php
namespace App\Controllers;

use App\Models\SessionModel;
use App\Models\User;
use App\Middleware\AuthMiddleware;
use App\Core\Session;

class AdminController
{
    private SessionModel $sessionModel;
    private User $userModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
        $this->userModel = new User();
    }

    public function sessions(): void
    {
        AuthMiddleware::adminOnly();

        $sessions = $this->sessionModel->getAll();

        require __DIR__ . '/../Views/admin/sessions.php';
    }

    public function users(): void
    {
        AuthMiddleware::adminOnly();

        $users = $this->userModel->getAll();

        require __DIR__ . '/../Views/admin/users.php';
    }

    public function revokeSession(): void
    {
        AuthMiddleware::adminOnly();

        if (isset($_GET['session']) && !empty(trim($_GET['session']))) {
            $this->sessionModel->deleteBySession($_GET['session']);
            Session::flash('success', 'Sesión revocada exitosamente.');
        } else {
            Session::flash('error', 'Sesión inválida.');
        }

        header("Location: ?action=admin_sessions");
        exit;
    }

    public function deleteUser(): void
    {
        AuthMiddleware::adminOnly();

        if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $this->userModel->delete((int) $_GET['id']);
            Session::flash('success', 'Usuario eliminado.');
        } else {
            Session::flash('error', 'ID de usuario inválido.');
        }

        header("Location: ?action=admin_users");
        exit;
    }
}