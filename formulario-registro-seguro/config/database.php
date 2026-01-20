<?php

class Database {
    private $pdo;
    private static $instance = null;
    
    private function __construct() {
        // Cargar variables de entorno de forma segura
        $config = $this->loadConfig();
        
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']}",
                PDO::ATTR_PERSISTENT => false
            ];
            
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);

            // debug
            if (getenv('APP_ENV') === 'development') {
                $dbName = $this->pdo->query("SELECT DATABASE()")->fetchColumn();
                error_log("PHP conectado a la BD: " . $dbName);
            }
            
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos (detalles omitidos por seguridad)");
            throw new Exception("No se pudo conectar a la base de datos. Contacta al administrador.");
        }
    }
    
    private function loadConfig() {
        $envPath = __DIR__ . '/../.env';

        if (file_exists($envPath)) {
            $env = parse_ini_file($envPath);

            return [
                'host' => getenv('DB_HOST') ?: ($env['DB_HOST'] ?? '127.0.0.1'),
                'port' => getenv('DB_PORT') ?: ($env['DB_PORT'] ?? '3306'),
                'dbname' => getenv('DB_NAME') ?: ($env['DB_NAME'] ?? 'registro_seguro'),
                'username' => getenv('DB_USER') ?: ($env['DB_USER'] ?? 'root'),
                'password' => getenv('DB_PASSWORD') ?: ($env['DB_PASSWORD'] ?? ''),
                'charset' => getenv('DB_CHARSET') ?: ($env['DB_CHARSET'] ?? 'utf8mb4')
            ];
        }

        return [
            'host' => '127.0.0.1',
            'port' => '3306',
            'dbname' => 'registro_seguro',
            'username' => 'root',
            'password' => '', 
            'charset' => 'utf8mb4'
        ];
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}