<?php
// ORM.php
require_once __DIR__ . '../../config/database.php';

abstract class ORM {
    protected $table;
    protected $primaryKey = 'id';
    protected $attributes = [];
    protected $pdo;
    protected $errors = [];

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->attributes = [];  
    }

    /* =====================
       Methods
    ====================== */
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }

    public function getPrimaryKeyValue() {
    return $this->attributes[$this->primaryKey] ?? null;
}

    /* =====================
       Validaciones
    ====================== */
    public function validate() {
        $this->errors = [];
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    /* =====================
       Guardar (INSERT / UPDATE)
    ====================== */
    public function save() {
        if (!$this->validate()) {
            return false;
        }

        $pk = $this->primaryKey;

        // UPDATE
        if (!empty($this->attributes[$pk])) {
            $set = [];
            $values = [];

            foreach ($this->attributes as $key => $value) {
                if ($key !== $pk) {
                    $set[] = "$key = ?";
                    $values[] = $value;
                }
            }

            $values[] = $this->attributes[$pk];

            $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE $pk = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($values);
        }

        // INSERT
        $columns = array_keys($this->attributes);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute(array_values($this->attributes))) {
            $this->attributes[$pk] = $this->pdo->lastInsertId();
            return true;
        }

        return false;
    }

    /* =====================
       Buscar por ID
    ====================== */
  public static function find($id) {
    $instance = new static();
    $pk = $instance->primaryKey;

    $stmt = $instance->pdo->prepare(
        "SELECT * FROM {$instance->table} WHERE $pk = ? LIMIT 1"
    );
    $stmt->execute([$id]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        if (!is_array($instance->attributes)) {
            $instance->attributes = [];
        }
        $instance->attributes = $data;
        return $instance;
    }

    return null;
}
    /* =====================
       Obtener todos
    ====================== */
    public static function all() {
        $instance = new static();
        $stmt = $instance->pdo->query("SELECT * FROM {$instance->table}");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = [];

        foreach ($rows as $row) {
            $model = new static();
            $model->attributes = $row;
            $results[] = $model;
        }

        return $results;
    }

    /* =====================
       Where simple
    ====================== */
    public static function where($column, $value) {
        $instance = new static();

        $stmt = $instance->pdo->prepare(
            "SELECT * FROM {$instance->table} WHERE $column = ?"
        );
        $stmt->execute([$value]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = [];

        foreach ($rows as $row) {
            $model = new static();
            $model->attributes = $row;
            $results[] = $model;
        }

        return $results;
    }

    /* =====================
       Eliminar
    ====================== */
    public function delete() {
        $pk = $this->primaryKey;

        if (!empty($this->attributes[$pk])) {
            $stmt = $this->pdo->prepare(
                "DELETE FROM {$this->table} WHERE $pk = ?"
            );
            return $stmt->execute([$this->attributes[$pk]]);
        }

        return false;
    }
}
