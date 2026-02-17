<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\SessionModel;
use App\Core\Session;
use App\Core\CSRF;

class AuthController
{
    private User $userModel;
    private SessionModel $sessionModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->sessionModel = new SessionModel();
    }

   public function register(): void
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("POST received: " . print_r($_POST, true)); // Debug
        if (!CSRF::validate($_POST['csrf'] ?? '')) {
            error_log("CSRF failed"); // Debug
            Session::flash('error', 'Token CSRF inválido.');
            header("Location: ?route=register");
            exit;
        }

        $username = trim($_POST['username']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        error_log("Validated data: username=$username, email=$email, password length=" . strlen($password)); // Debug

        if (empty($username) || strlen($username) < 3 || !$email || strlen($password) < 8) {
            error_log("Validation failed"); // Debug
            Session::flash('error', 'Datos inválidos.');
            header("Location: ?route=register");
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            error_log("Email exists"); // Debug
            Session::flash('error', 'El email ya está registrado.');
            header("Location: ?route=register");
            exit;
        }

        error_log("Calling User::create"); // Debug
        if ($this->userModel->create($username, $email, $password)) {
            error_log("User created successfully"); // Debug
            Session::flash('success', 'Registro exitoso.');
            header("Location: ?route=login");
        } else {
            error_log("User create failed"); // Debug
            Session::flash('error', 'Error al registrar.');
            header("Location: ?route=register");
        }
        exit;
    }

    require __DIR__ . '/../Views/auth/register.php';
}

 public function login(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!\App\Core\CSRF::validate($_POST['csrf'] ?? '')) {
            \App\Core\Session::flash('error', 'Token CSRF inválido.');
            header("Location: ?route=login");
            exit;
        }

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        error_log("Login attempt: email=$email, password length=" . strlen($password)); // Debug

        if (!$email) {
            error_log("Email invalid"); // Debug
            \App\Core\Session::flash('error', 'Email inválido.');
            header("Location: ?route=login");
            exit;
        }

        $user = $this->userModel->findByEmail($email);
        error_log("User found: " . ($user ? 'yes' : 'no')); // Debug

        if ($user && password_verify($password, $user['password'])) {
            error_log("Password verified, redirecting to profile"); // Debug
            \App\Core\Session::regenerate();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $this->sessionModel->create($user['id']);

            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $hash = hash('sha256', $token);
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

                $this->userModel->updateRememberToken($user['id'], $hash, $expiry);

                setcookie("remember_token", $token, [
                    'expires' => time() + (60 * 60 * 24 * 30),
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            }

            header("Location: ?route=profile");
            exit;
        } else {
            error_log("Login failed: user=" . ($user ? 'found' : 'not found') . ", password=" . (password_verify($password, $user['password'] ?? '') ? 'verified' : 'not verified')); // Debug
            \App\Core\Session::flash('error', 'Credenciales incorrectas.');
            header("Location: ?route=login");
            exit;
        }
    }

    // Genera token solo para la vista (GET)
    $csrf_token = \App\Core\CSRF::generate();
    require __DIR__ . '/../Views/auth/login.php';
}

    public function profile(): void
    {
        if (!Session::isLoggedIn()) {
            if (isset($_COOKIE['remember_token'])) {
                $hash = hash('sha256', $_COOKIE['remember_token']);
                $user = $this->userModel->findByTokenHash($hash);

                if ($user) {
                    Session::regenerate();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    // El handler crea la sesión automáticamente
                } else {
                    header("Location: ?route=login");
                    exit;
                }
            } else {
                header("Location: ?route=login");
                exit;
            }
        }

        $user = $this->userModel->findById(Session::getUserId());
        require __DIR__ . '/../Views/user/profile.php';
    }

    public function logout(): void
    {
        if (Session::isLoggedIn()) {
            $this->sessionModel->deleteBySession(session_id());
            $this->userModel->clearRememberToken(Session::getUserId());
        }

        setcookie("remember_token", "", time() - 3600, "/");
        Session::destroy();

        header("Location: ?route=login");
        exit;
    }
}