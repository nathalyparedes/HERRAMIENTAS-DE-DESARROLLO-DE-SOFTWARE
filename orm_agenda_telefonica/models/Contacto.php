<?php
// models/Contacto.php
require_once 'ORM.php';

class Contacto extends ORM {
    protected $table = 'contacto';
    protected $primaryKey = 'id_contacto';

    public function __construct() {
        parent::__construct();
    }

    // Validaciones específicas para Contacto
    public function validate() {
        parent::validate();  // Llamar a la validación base

        // Nombre: No vacío, al menos 2 caracteres
        if (empty($this->attributes['nombre_contacto']) || strlen($this->attributes['nombre_contacto']) < 2) {
            $this->errors['nombre_contacto'] = 'El nombre debe tener al menos 2 caracteres.';
        }

        // Apellido: No vacío, al menos 2 caracteres
        if (empty($this->attributes['apellido_contacto']) || strlen($this->attributes['apellido_contacto']) < 2) {
            $this->errors['apellido_contacto'] = 'El apellido debe tener al menos 2 caracteres.';
        }

        // Teléfono: No vacío, formato básico (solo números, guiones, espacios)
        if (empty($this->attributes['telefono_contacto']) || !preg_match('/^[0-9\-\s]+$/', $this->attributes['telefono_contacto'])) {
            $this->errors['telefono_contacto'] = 'El teléfono debe contener solo números, guiones y espacios.';
        }

        // Email: Opcional, pero si se proporciona, debe ser válido
        if (!empty($this->attributes['email_contacto']) && !filter_var($this->attributes['email_contacto'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email_contacto'] = 'El email no es válido.';
        }

        return empty($this->errors);
    }
}
