<?php
// controllers/LoginController.php
require_once 'models/Persona.php';

class LoginController {
    public function login() {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $persona = Persona::authenticate($_POST['usuario'], $_POST['contraseña']);
            if ($persona) {
                $_SESSION['persona_id'] = $persona->id_persona;
                header('Location: index.php?action=contactos');
                exit;
            } else {
                $errors['general'] = 'Credenciales incorrectas.';
            }
        }
        include 'views/auth/login.php';
    }

    public function registro() {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $persona = new Persona();
            $persona->nombre_persona = $_POST['nombre_persona'] ?? '';
            $persona->apellido_persona = $_POST['apellido_persona'] ?? '';
            $persona->usuario = $_POST['usuario'] ?? '';
            $persona->contraseña = password_hash($_POST['contraseña'] ?? '', PASSWORD_DEFAULT);
            if ($persona->save()) {
                header('Location: index.php?action=login');
                exit;
            } else {
                $errors = $persona->getErrors();
            }
        }
        $persona = null;  // Para registro, objeto vacío
        $action = 'registro';
        include 'views/auth/form_persona.php';
    }

    public function editar_persona() {
        if (!isset($_SESSION['persona_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $persona = Persona::find($_SESSION['persona_id']);
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($persona->updateProfile($_POST)) {
                header('Location: index.php?action=contactos');
                exit;
            } else {
                $errors = $persona->getErrors();
            }
        }
        $action = 'editar_persona';
        include 'views/auth/form_persona.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
?>