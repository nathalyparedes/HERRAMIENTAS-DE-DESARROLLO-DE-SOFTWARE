<?php
require_once './config/database.php';

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    
    // Verificar si email existe
    public function emailExists($email) {
    try {
        $sql = "SELECT 1 FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch() !== false;
    } catch (PDOException $e) {
        error_log("Error verificando email: " . $e->getMessage());
        return false;
    }
}

    
    // Registrar nuevo usuario
    public function register($userData) {
        try {
            // Validar datos básicos
            $required = ['nombre','email', 'telefono', 'password', 'fecha_nacimiento'];
            foreach ($required as $field) {
                if (!isset($userData[$field]) || trim($userData[$field]) === '') {
                throw new Exception("Campo requerido: $field");
            }

            }
            
            // Validar email único
            if ($this->emailExists($userData['email'])) {
                throw new Exception("El email ya está registrado");
            }
            
            
            // Validar que aceptó términos
            if (!isset($userData['terminos']) || !$userData['terminos']) {
                throw new Exception("Debe aceptar los términos y condiciones");
            }
            
            $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);

            // Formatear fecha (si viene en otro formato
            $fecha_nacimiento = $this->formatDate($userData['fecha_nacimiento']);
            
            // Insertar usuario
            $sql = "INSERT INTO usuarios (
                nombre,
                email,
                telefono,
                fecha_nacimiento,
                password_hash,
                acepto_terminos
            ) VALUES (
                :nombre,
                :email,
                :telefono,
                :fecha_nacimiento,
                :password_hash,
                :acepto_terminos
            )";
            
            $stmt = $this->db->prepare($sql);
            
            $success = $stmt->execute([
                ':nombre' => $userData['nombre'],
                ':email' => $userData['email'],
                ':telefono' => $userData['telefono'] ?? null,
                ':fecha_nacimiento' => $fecha_nacimiento,
                ':password_hash' => $passwordHash,
                ':acepto_terminos' => $userData['terminos'] ? 1 : 0
            ]);
            
            if ($success) {
                return [
                    'success' => true,
                    'message' => 'Usuario registrado exitosamente'
                ];
            } else {
                throw new Exception("Error al insertar usuario");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    // Formatear fecha de dd/mm/yyyy a yyyy-mm-dd
   private function formatDate($dateStr) {
    $date = DateTime::createFromFormat('d/m/Y', $dateStr);
    if ($date) {
        return $date->format('Y-m-d');
    }

    // Fformato ISO
    $date = DateTime::createFromFormat('Y-m-d', $dateStr);
    if ($date) {
        return $dateStr;
    }

    throw new Exception("Formato de fecha inválido");
}

}