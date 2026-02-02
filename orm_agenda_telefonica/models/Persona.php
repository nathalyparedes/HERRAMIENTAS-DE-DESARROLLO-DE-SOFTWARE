<?php
// models/Persona.php
require_once 'ORM.php';

class Persona extends ORM {
    protected $table = 'persona';
    protected $primaryKey = 'id_persona';

    public function __construct() {
        parent::__construct();
    }

    // Validaciones específicas para Persona
    public function validate() {
        parent::validate();  // Llamar a la validación base

        // Nombre: No vacío, al menos 2 caracteres
        if (empty($this->attributes['nombre_persona']) || strlen($this->attributes['nombre_persona']) < 2) {
            $this->errors['nombre_persona'] = 'El nombre debe tener al menos 2 caracteres.';
        }

        // Apellido: No vacío, al menos 2 caracteres
        if (empty($this->attributes['apellido_persona']) || strlen($this->attributes['apellido_persona']) < 2) {
            $this->errors['apellido_persona'] = 'El apellido debe tener al menos 2 caracteres.';
        }

        // Usuario: No vacío, único, al menos 3 caracteres
        if (empty($this->attributes['usuario']) || strlen($this->attributes['usuario']) < 3) {
            $this->errors['usuario'] = 'El usuario debe tener al menos 3 caracteres.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $this->attributes['usuario'])) {
            $this->errors['usuario'] = 'El usuario solo puede contener letras, números y guiones bajos.';
        } else {
            // Verificar unicidad (solo en insert, no en update)
            $existing = self::where('usuario', $this->attributes['usuario']);
            if (count($existing) > 0 && (!isset($this->attributes['id_persona']) || $existing[0]->id_persona != $this->attributes['id_persona'])) {
                $this->errors['usuario'] = 'El usuario ya existe.';
            }
        }

        // Contraseña: Solo validar si se proporciona (para edición)
        if (!empty($this->attributes['contraseña']) && strlen($this->attributes['contraseña']) < 6) {
            $this->errors['contraseña'] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        return empty($this->errors);
    }

    // Método para actualizar perfil (solo para el usuario logueado)
    public function updateProfile($data) {
        if (!isset($_SESSION['persona_id']) || $this->id_persona != $_SESSION['persona_id']) {
            return false;  // Seguridad: solo el propietario puede editar
        }
        $this->nombre_persona = $data['nombre_persona'] ?? $this->nombre_persona;
        $this->apellido_persona = $data['apellido_persona'] ?? $this->apellido_persona;
        $this->usuario = $data['usuario'] ?? $this->usuario;
        if (!empty($data['contraseña'])) {
            $this->contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT);
        }
        return $this->save();
    }

    // Método para verificar login
    public static function authenticate($usuario, $contraseña) {
        $personas = self::where('usuario', $usuario);
        if (count($personas) > 0 && password_verify($contraseña, $personas[0]->contraseña)) {
            return $personas[0];
        }
        return null;
    }

    // Relación: Una persona tiene muchos contactos
    public function contactos() {
        return Contacto::where('persona_id', $this->id_persona);
    }
}
?>