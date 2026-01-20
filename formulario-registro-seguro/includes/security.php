<?php
// security.php - Funciones de seguridad

class Security {
    
    // Sanitizar entrada
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map('self::sanitize', $input);
        }
        
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }
    
    // Validar email
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && 
               preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
    }
    
    // Validar teléfono
    public static function validatePhone($phone) {
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);
        return preg_match('/^\+?[1-9]\d{1,14}$/', $cleaned);
    }
    
    // Validar fecha de nacimiento
    public static function validateBirthDate($date) {
        if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            return false;
        }
        
        list($day, $month, $year) = explode('/', $date);
        
        if (!checkdate($month, $day, $year)) {
            return false;
        }
        
        // Verificar edad mínima (18 años)
        $birthDate = DateTime::createFromFormat('d/m/Y', $date);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        
        return $age >= 18;
    }
    
    // Validar contraseña
    public static function validatePasswordStrength($password) {
        if (strlen($password) < 8) {
            return ['valid' => false, 'error' => 'Mínimo 8 caracteres'];
        }
        
        $errors = [];
        if (!preg_match('/[A-Z]/', $password)) $errors[] = 'Una mayúscula';
        if (!preg_match('/[a-z]/', $password)) $errors[] = 'Una minúscula';
        if (!preg_match('/[0-9]/', $password)) $errors[] = 'Un número';
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) $errors[] = 'Un carácter especial';
        
        if (!empty($errors)) {
            return ['valid' => false, 'error' => implode(', ', $errors)];
        }
        
        return ['valid' => true, 'score' => self::calculatePasswordScore($password)];
    }
    
    private static function calculatePasswordScore($password) {
        $score = 0;
        $length = strlen($password);
        
        $score += min($length * 4, 40);
        if (preg_match('/[A-Z]/', $password)) $score += 10;
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[0-9]/', $password)) $score += 10;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 15;
        
        return min(max($score, 0), 100);
    }
    
    // Hash de contraseña
    public static function hashPassword($password, &$salt = null) {
        if ($salt === null) {
            $salt = bin2hex(random_bytes(16));
        }
        
        $options = [
            'cost' => 12,
            'salt' => $salt
        ];
        
        return password_hash($password . $salt, PASSWORD_BCRYPT, $options);
    }
    
    
}
?>