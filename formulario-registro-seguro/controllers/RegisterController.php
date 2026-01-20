<?php
// RegisterController.php
class RegisterController {
    private $model;

    public function __construct() {
        require_once MODELS . '/UserModel.php';
        $this->model = new UserModel();
    }

    public function processRegistration() {
        $errors = [];
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

        // 1. Recibir y sanitizar datos
        $data = [
            'nombre'            => Security::sanitize($_POST['nombre'] ?? ''),
            'email'             => Security::sanitize($_POST['email'] ?? ''),
            'telefono'          => Security::sanitize($_POST['telefono'] ?? ''),
            'password'          => $_POST['password'] ?? '',  
            'password_confirm'  => $_POST['password_confirm'] ?? '',  
            'fecha_nacimiento'  => Security::sanitize($_POST['fecha_nacimiento'] ?? ''),
            'terminos'          => isset($_POST['terminos'])
        ];

        // 2. Validaciones 
        if ($data['nombre'] === '') {
            $errors[] = 'El nombre es requerido';
        }
        if ($data['email'] === '') {
            $errors[] = 'El email es requerido';
        } elseif (!Security::validateEmail($data['email'])) {
            $errors[] = 'Email inválido';
        }
        if ($data['telefono'] === '') {
            $errors[] = 'El teléfono es requerido';
        }
        $passwordValidation = Security::validatePasswordStrength($data['password']);
        if (!$passwordValidation['valid']) {
            $errors[] = 'Contraseña insegura: ' . $passwordValidation['error'];
        }
        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        if ($data['fecha_nacimiento'] === '') {
            $errors[] = 'La fecha de nacimiento es requerida';
        } else {
            try {
                $birthDate = new DateTime($data['fecha_nacimiento']);
                $today = new DateTime();
                $age = $today->diff($birthDate)->y;
                if ($age < 18) {
                    $errors[] = 'Debes ser mayor de 18 años';
                }
            } catch (Exception $e) {
                $errors[] = 'Formato de fecha inválido';
            }
        }
        if (!$data['terminos']) {
            $errors[] = 'Debes aceptar los términos y condiciones';
        }

        // 3. Si hay errores
        if (!empty($errors)) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            } else {
                header('Location: ?action=form&error=' . urlencode(implode(', ', $errors)));
                exit;
            }
        }

        // 4. Registrar usuario
        $result = $this->model->register($data);

        if (!$result['success']) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'errors' => [$result['error']]]);
                exit;
            } else {
                header('Location: ?action=form&error=' . urlencode($result['error']));
                exit;
            }
        }

        // 5. Éxito
        if ($isAjax) {
            echo json_encode(['success' => true, 'message' => $result['message']]);
            exit;
        } else {
            header('Location: ?action=form&success=' . urlencode($result['message']));
            exit;
        }
    }

    public function showSuccess() { 
        $nombre = Security::sanitize($_GET['nombre'] ?? '');
        $email = Security::sanitize($_GET['email'] ?? ''); 
        $telefono = Security::sanitize($_GET['telefono'] ?? '');    
        $fecha_nacimiento = Security::sanitize($_GET['fecha_nacimiento'] ?? '');
        $data = [ 
            'title' => 'Registro Exitoso', 
            'email' => $email, 
            'nombre' => $nombre,
            'telefono' => $telefono,
            'fecha_nacimiento' => $fecha_nacimiento
        ]; 
        $this->renderView('register/success.php', $data); 
    }

    public function showForm() {
        $data = [
            'title' => 'Registro de Usuario'
        ];
        $this->renderView('register/form.php', $data);
    }

    private function renderView($viewFile, $data = []) {
        extract($data);
        require_once INCLUDES . '/header.php';
        require_once VIEWS . '/' . $viewFile;
        require_once INCLUDES . '/footer.php';
    }
}